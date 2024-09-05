<?php
/*
 * Template Name: Nowe ogłoszenie
 * Template Post Type: page
 */
?>
<?php
    session_start();
?>
<?php
    if(!isset($_SESSION['Wspolnota'])){
        header('Location:'.'http://e-pzn.pl');
        die;
    }
?>
<?php
    $Wspolnota = $_SESSION['Wspolnota']; 
?>
<?php 
    // Two checks for safety
    // TODO SANITIZATION AND SECURITY
    if(isset($_POST['NewTitle'])){
        if(strlen($_POST['NewTitle']) >= 8){
            $Posts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'ogloszenie',
            'tax_query' => array(
                array(
                    'taxonomy' => 'wspolnoty',
                    'field' => 'name',
                    'terms' => $Wspolnota
                )
            ),
            'orderby' => 'title',
            'order' => 'ASC'));

            $PostExists = false;
            foreach($Posts as $Post){
                if($Post->post_title === $_POST['NewTitle']){
                    $PostExists = true;
                    break;
                }
            }
            
            if(!$PostExists){
                $FilesError = false;
                $FilesCount = count($_FILES['files']['name']);
                echo($FilesCount);
                if($FilesCount > 5){
                    $FilesError = true;
                }

                if(!$FilesError){
                    for($i=0;$i<$FilesCount;$i++){
                    if($_FILES["files"]["size"][$i] > 5000000){
                        $FilesError = true;
                        break;
                    }

                    $FileExtension = pathinfo($_FILES["files"]["name"][$i],PATHINFO_EXTENSION);
                    if($FileExtension != "jpg" && $FileExtension != "png" && $FileExtension != "jpeg" && $FileExtension != "gif" && $FileExtension != "pdf"){
                        $FilesError = true;
                        break;
                    }   
                    }

                    if(!$FilesError){
                        $postarr = array(
                            'post_title' => sanitize_text_field($_POST['NewTitle']),
                            'post_content' => sanitize_text_field($_POST['NewContent']),
                            'post_type' => 'ogloszenie',
                            'post_status' => 'publish',
                            'comment_status' => 'closed',
                            'ping_status' => 'closed', 
                        );
                        $post_id = wp_insert_post($postarr);
                        // Set post template
                        update_post_meta( $post_id, '_wp_page_template', 'single-ogloszenie.php' );
                        // Set terms
                        wp_set_object_terms($post_id,$Wspolnota,'wspolnoty');
                        // Set ACF post information
                        update_field('data_wpisu',current_time("Y-m-d"),$post_id);
                        // Upload files
                        $FileDir = WP_CONTENT_DIR . '/user-uploads/';
                        if (!file_exists($FileDir . $post_id)){
                            wp_mkdir_p($FileDir . $post_id);
                        }
                        echo($FilesCount);
                        for($i=0;$i<$FilesCount;$i++){
                            echo($i);
                            $FileName = $_FILES['files']['name'][$i];
                            if (move_uploaded_file($_FILES['files']['tmp_name'][$i],'wp-content/user-uploads/' . $post_id . "/" . $FileName)) {
                                add_row('zalaczniki',array('nazwa_pliku' => $FileName),$post_id);
                            } else {
                                echo "A error has occured uploading.";
                            }
                        }
                        // Redirect after submiting
                        // TODO change to https when we will have SSL
                        header('Location:'. get_permalink($post_id));
                        die;
                    }else{
                        echo("<script>alert(\"There was a problem with files upload\");</script>");
                    }
                }else{
                    echo("<script>alert(\"There was a problem with files upload\");</script>");
                }
            }
        }
    }
?>
<?php
    get_header();
?>
<main id="site-content" role="main">
    <div class="Wrapper NoweOgloszenie">
        <div id="NewForm">
            <form method="POST" enctype='multipart/form-data'>
                <h5>Dodaj nowe ogłoszenie:</h5>
                <button type="button" class="BackButton" onclick="goBackDefault()">Powrót</button>
                <input id="NewTitle" type="text" name="NewTitle" minlength="8" maxlength="64" placeholder="Dodaj tytuł Twojego ogłoszenia..." />
                <textarea id="NewContent" type="text" name="NewContent" minlength="16" maxlength="256" placeholder="Dodaj treść Twojego ogłoszenia..."></textarea>
                <input type="file" id="files" name="files[]" multiple/>
                <input type="submit" value="Dodaj ogłoszenie"/>
            </form>
        </div>
    </div>
<?php get_footer(); ?>