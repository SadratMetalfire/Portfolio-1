<?php
    require('/wp-blog-header.php');
?>

<?php 
    // ToDo: petla po każdej wspolnocie i synchronizacja bazowana na wspólnotach
    $UsersAccountsFileName = "UsersAccounts.csv";
    $AccountSummaryFileName = "AccountSummary.csv";
    $DocumentsFileName = "Documents.csv";
    $Wspolnota = "smz";

    if(file_exists($UsersAccountsFileName)){
        SyncUsersAccounts($UsersAccountsFileName,$Wspolnota);
        unlink($UsersAccountsFileName);
    }
    if(file_exists($AccountSummaryFileName)){
        SyncAccountSummary($AccountSummaryFileName,$Wspolnota);
        unlink($AccountSummaryFileName);
    }
    if(file_exists($DocumentsFileName)){
        SyncDocuments($DocumentsFileName,$Wspolnota);
        unlink($DocumentsFileName);
    }
?>

<?php
    function SyncUsersAccounts($Filename,$Wspolnota){
        $Posts = get_posts(array( 
            'post_type' => 'konto',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));
        $file = fopen($Filename, "r");
        $line = 0;
        $DataBaseIds = [];
        $PostsCount = sizeof($Posts);
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE){
            if($line != 0){
                if($getData[4] != ""){
                    $Posts = get_posts(array( 
                        'post_type' => 'konto',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'exclude' => $DataBaseIds,
                    ));

                    $FoundInDataBase = false;

                    foreach($Posts as $Post){
                        if($getData[4] == $Post->post_title){
                            $FoundInDataBase = true;
                            array_push($DataBaseIds,$Post->ID); //Add to array
                            $post_id = $Post->ID;
                            //Update
                            update_field('typ_konta','Mieszkaniec',$post_id);
                            delete_post_meta($post_id,'zzresourcename');
                            $rows = explode(',',$getData[2]);
                            for($i= 0 ; $i < count($rows); $i++){
                                $elements = explode('/',$rows[$i]);
                                $elements[0] = str_replace(' ', '', $elements[0]);
                                $elements[1] = str_replace(' ', '', $elements[1]);
                                $id = add_row('zzresourcename',array('pietro' => $elements[0], 'nrmieszkania' => $elements[1]),$post_id);
                            }
                            wp_set_object_terms($post_id,$Wspolnota,'wspolnoty');
                            update_field('zzadress',$getData[3],$post_id);
                            update_field('idzz',$getData[1],$post_id);
                            update_field('zznrb',$getData[5],$post_id);
                            update_field('zzcustomername',$getData[6],$post_id);
                            update_field('gsmtel',$getData[7],$post_id);
                            update_field('email',$getData[8],$post_id);
                            update_field('login',$getData[9],$post_id);
                            update_field('haslo',password_hash($getData[10],PASSWORD_DEFAULT),$post_id);
                            echo($getData[11]);
                            echo(get_field('active',$post_id));
                            update_field('active',$getData[11],$post_id); 
                            break;
                        }
                    }

                    if(!$FoundInDataBase){
                        //Add
                        $postarr = array(
                            'post_title' => $getData[4],
                            'post_type' => 'konto',
                            'post_status' => 'publish',
                            'comment_status' => 'closed',
                            'ping_status' => 'closed', 
                        );
                        $post_id = wp_insert_post($postarr);
                        array_push($DataBaseIds,$post_id); //Add to array
                        $PostsCount++; //More post count
                        // Set ACF post information
                        update_field('typ_konta','Mieszkaniec',$post_id);
                        $rows = explode(',',$getData[2]);
                        for($i= 0 ; $i < count($rows); $i++){
                            $elements = explode('/',$rows[$i]);
                            $elements[0] = str_replace(' ', '', $elements[0]);
                            $elements[1] = str_replace(' ', '', $elements[1]);
                            $id = add_row('zzresourcename',array('pietro' => $elements[0], 'nrmieszkania' => $elements[1]),$post_id);
                        }
                        wp_set_object_terms($post_id,$Wspolnota,'wspolnoty');
                        update_field('zzadress',$getData[3],$post_id);
                        update_field('idzz',$getData[1],$post_id);
                        update_field('zznrb',$getData[5],$post_id);
                        update_field('zzcustomername',$getData[6],$post_id);
                        update_field('gsmtel',$getData[7],$post_id);
                        update_field('email',$getData[8],$post_id);
                        update_field('login',$getData[9],$post_id);
                        update_field('haslo',password_hash($getData[10],PASSWORD_DEFAULT),$post_id);
                        update_field('active',$getData[11],$post_id); 
                    }
                }                         
            }else{
                $line ++;
            }
        }
        if(sizeof($DataBaseIds) < $PostsCount){
            //Remove
            $Posts = get_posts(array( 
                'post_type' => 'konto',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'exclude' => $DataBaseIds,
            ));
            foreach($Posts as $Post){
                wp_delete_post($Post->ID);
            }
        }
        fclose($file);
    }
?>

<?php
    function SyncAccountSummary($Filename,$Wspolnota){
        $Posts = get_posts(array( 
            'post_type' => 'rozliczenia',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));
        $file = fopen($Filename, "r");
        $line = 0;
        $CurrentCustomer = null;
        $post_id = null;
        $DataBaseIds = [];
        $PostsCount = sizeof($Posts);
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE){
            if($line != 0){
                if($getData[2] != ""){
                    $Posts = get_posts(array( 
                        'post_type' => 'rozliczenia',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'exclude' => $DataBaseIds,
                    ));

                    $FoundInDataBase = false;

                    foreach($Posts as $Post){
                        if($getData[2] == $Post->post_title){
                            $FoundInDataBase = true;
                            //Update
                            if($CurrentCustomer != $getData[2]){
                                $CurrentCustomer = $getData[2];
                                $post_id = $Post->ID;
                                wp_set_object_terms($post_id,$Wspolnota,'wspolnoty');
                                update_field('idzz',$getData[1],$post_id);
                                array_push($DataBaseIds,$post_id); //Add to array
                                delete_post_meta($post_id,'inner');
                            }
                            $id = add_row('inner',array(
                                'year' => $getData[3],
                                'month' => $getData[4],
                                'liability' => $getData[5],
                                'paid' => $getData[6],
                                'topay' => $getData[7],
                                'overpayment' => $getData[8],
                            ),$post_id); 
                            $PostsCount++; //More post count
                        }
                    }

                    if(!$FoundInDataBase){
                        //Add
                        if($CurrentCustomer != $getData[2]){
                            $CurrentCustomer = $getData[2];
                            $postarr = array(
                                'post_title' => $getData[2],
                                'post_type' => 'rozliczenia',
                                'post_status' => 'publish',
                                'comment_status' => 'closed',
                                'ping_status' => 'closed', 
                            );
                            $post_id = wp_insert_post($postarr);
                            wp_set_object_terms($post_id,$Wspolnota,'wspolnoty');
                            update_field('idzz',$getData[1],$post_id);
                            array_push($DataBaseIds,$post_id); //Add to array
                        }
                        $id = add_row('inner',array(
                            'year' => $getData[3],
                            'month' => $getData[4],
                            'liability' => $getData[5],
                            'paid' => $getData[6],
                            'topay' => $getData[7],
                            'overpayment' => $getData[8],
                        ),$post_id); 
                        $PostsCount++; //More post count
                    }
                }                      
            }else{
                $line ++;
            }
        }
        if(sizeof($DataBaseIds) < $PostsCount){
            //Remove
            $Posts = get_posts(array( 
                'post_type' => 'rozliczenia',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'exclude' => $DataBaseIds,
            ));
            foreach($Posts as $Post){
                wp_delete_post($Post->ID);
            }
        }
        fclose($file);
    }
?>

<?php
    function SyncDocuments($Filename,$Wspolnota){
        $Posts = get_posts(array( 
            'post_type' => 'dokumenty',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));
        $file = fopen($Filename, "r");
        $line = 0;
        $DataBaseIds = [];
        $PostsCount = sizeof($Posts);
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE){
            if($line != 0){
                if($getData[1] != ""){
                    $Posts = get_posts(array( 
                        'post_type' => 'dokumenty',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'exclude' => $DataBaseIds,
                    ));

                    $FoundInDataBase = false;

                    foreach($Posts as $Post){
                        if($getData[5] == $Post->post_title){
                            $FoundInDataBase = true;
                            array_push($DataBaseIds,$Post->ID); //Add to array
                            //Update
                            $post_id = $Post->ID;
                            wp_set_object_terms($post_id,$Wspolnota,'wspolnoty');
                            update_field('iddocument',$getData[1],$post_id);      
                            update_field('year',$getData[2],$post_id);      
                            update_field('month',$getData[3],$post_id);      
                            update_field('typename',$getData[4],$post_id);      
                            update_field('filename',$getData[6],$post_id);
                            break;
                        }
                    }

                    if(!$FoundInDataBase){
                        //Add
                        $postarr = array(
                            'post_title' => $getData[5],
                            'post_type' => 'dokumenty',
                            'post_status' => 'publish',
                            'comment_status' => 'closed',
                            'ping_status' => 'closed', 
                        );
                        $post_id = wp_insert_post($postarr);
                        array_push($DataBaseIds,$post_id); //Add to array
                        $PostsCount++; //More post count
                        wp_set_object_terms($post_id,$Wspolnota,'wspolnoty');
                        // Set ACF post information
                        // update_field('idcommunitywww',$getData[0],$post_id);
                        update_field('iddocument',$getData[1],$post_id);      
                        update_field('year',$getData[2],$post_id);      
                        update_field('month',$getData[3],$post_id);      
                        update_field('typename',$getData[4],$post_id);      
                        update_field('filename',$getData[6],$post_id);
                    }               
                }
            }else{
                $line ++;
            }
        }
        if(sizeof($DataBaseIds) < $PostsCount){
            //Remove
            $Posts = get_posts(array( 
                'post_type' => 'dokumenty',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'exclude' => $DataBaseIds,
            ));
            foreach($Posts as $Post){
                wp_delete_post($Post->ID);
            }
        }
        fclose($file);
    }
?>