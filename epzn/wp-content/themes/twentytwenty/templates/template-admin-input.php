<?php
/**
 * Template Name: Admin input
 * Template Post Type: page
 */
?>
<?php
    session_start();
?>
<?php
    $Wspolnota = $_SESSION['Wspolnota']; 
?>
<?php 
    if(isset($_POST["ImportUsers"])){
        $filename=$_FILES["File"]["tmp_name"];    
        if($_FILES["File"]["size"] > 0){
            $Posts = get_posts(array( 
                'post_type' => 'konto',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            ));
            $file = fopen($filename, "r");
            $line = 0;
            $DataBaseIds = [];
            $PostsCount = sizeof($Posts);
            while (($getData = fgetcsv($file, 10000, ";")) !== FALSE){
                if($line != 0){
                    if($getData[1] != ""){
                        $Posts = get_posts(array( 
                            'post_type' => 'konto',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'exclude' => $DataBaseIds,
                        ));

                        $FoundInDataBase = false;

                        foreach($Posts as $Post){
                            if($getData[1] == $Post->post_title){
                                $FoundInDataBase = true;
                                array_push($DataBaseIds,$Post->ID); //Add to array
                                //Update
                                update_field('typ_konta','Mieszkaniec',$post_id);
                                delete_post_meta($post_id,'zzresourcename');
                                $rows = explode(',',$getData[2]);
                                for($i= 0 ; $i < count($rows); $i++){
                                    $elements = explode('/',$rows[$i]);
                                    $id = add_row('zzresourcename',array('pietro' => $elements[0], 'nrmieszkania' => $elements[1]),$post_id);
                                }
                                // update_field('idcommunity',$getData[0],$post_id);
                                update_field('zzadress',$getData[3],$post_id);
                                update_field('idzzcustomer',$getData[4],$post_id);
                                update_field('zznrb',$getData[5],$post_id);
                                update_field('zzcustomername',$getData[6],$post_id);
                                update_field('gsmtel',$getData[7],$post_id);
                                update_field('email',$getData[8],$post_id);
                                update_field('login',$getData[9],$post_id);
                                update_field('haslo',password_hash($getData[10],PASSWORD_DEFAULT),$post_id);
                                update_field('active',$getData[11],$post_id); 
                                break;
                            }
                        }

                        if(!$FoundInDataBase){
                            //Add
                            $postarr = array(
                                'post_title' => $getData[1],
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
                                $id = add_row('zzresourcename',array('pietro' => $elements[0], 'nrmieszkania' => $elements[1]),$post_id);
                            }
                            // update_field('idcommunity',$getData[0],$post_id);
                            update_field('zzadress',$getData[3],$post_id);
                            update_field('idzzcustomer',$getData[4],$post_id);
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
        // Redirect after submiting
        // TODO change to https when we will have SSL
        header('Location:'.'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        die;
    }else if(isset($_POST["ImportDocuments"])){
        $filename=$_FILES["File"]["tmp_name"];    
        if($_FILES["File"]["size"] > 0){
            $Posts = get_posts(array( 
                'post_type' => 'dokumenty',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            ));
            $file = fopen($filename, "r");
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
        // Redirect after submiting
        // TODO change to https when we will have SSL
        header('Location:'.'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        die;
    }else if(isset($_POST["ImportRozliczenia"])){
        $filename=$_FILES["File"]["tmp_name"];    
        if($_FILES["File"]["size"] > 0){
            $Posts = get_posts(array( 
                'post_type' => 'rozliczenia',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            ));
            $file = fopen($filename, "r");
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
        // Redirect after submiting
        // TODO change to https when we will have SSL
        header('Location:'.'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        die;
    }      
?>
<?php
    get_header();
?>
<div style="width:50%;display:block;margin:auto;margin-top:75px;">
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="File" id="File">
        <input type="submit" name="ImportUsers" value="Import Users" data-loading-text="Loading...">
    </form>
    </br>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="File" id="File">
        <input type="submit" name="ImportDocuments" value="Import Documents" data-loading-text="Loading...">
    </form>
    </br>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="File" id="File">
        <input type="submit" name="ImportRozliczenia" value="Import Rozliczenia" data-loading-text="Loading...">
    </form>
</div>
<?php get_footer(); ?>