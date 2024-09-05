<?php
/*
 * Template Name: Nowy wpis
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
    if(isset($_GET['term_id'])){
        $Watek = get_term($_GET['term_id']);
        $term_id = $_GET['term_id'];
    }else{
        $Watek = get_term($_POST['term_id']);
        $term_id = $_POST['term_id'];
    }
    $Wspolnota = $_SESSION['Wspolnota']; 
    $Nazwa = $_SESSION["Nazwa"];
?>
<?php 
    // Two checks for safety
    // TODO SANITIZATION AND SECURITY
    if(isset($_POST['NewPostTitle'])){
        if(strlen($_POST['NewPostTitle']) >= 8){
            $Posts = get_posts(array(
            'showposts' => -1,
            'post_type' => $Wspolnota,
            'tax_query' => array(
                array(
                    'taxonomy' => 'watki',
                    'field' => 'term',
                    'terms' => $_POST['term_id']
                )
            ),
            'orderby' => 'title',
            'order' => 'ASC'));

            $PostExists = false;
            foreach($Posts as $Post){
                if($Post->post_title === sanitize_text_field($_POST['NewPostTitle'])){
                    $PostExists = true;
                    break;
                }
            }
            
            if(!$PostExists){
                $FilesError = false;
                $FilesCount = count($_FILES['files']['name']);
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
                            'post_title' => sanitize_text_field($_POST['NewPostTitle']),
                            'post_content' => sanitize_text_field($_POST['NewPostContent']),
                            'post_type' => $Wspolnota,
                            'post_status' => 'publish',
                            'comment_status' => 'closed',
                            'ping_status' => 'closed', 
                        );
                        $post_id = wp_insert_post($postarr);
                        // Set post template
                        update_post_meta( $post_id, '_wp_page_template', 'single.php' );
                        // Set terms
                        wp_set_object_terms($post_id,get_term($_POST['term_id'])->name,'watki');
                        // Set ACF post information
                        update_field('autor_wpisu',$Nazwa,$post_id);
                        update_field('data_wpisu',current_time("Y-m-d"),$post_id);
                        // Upload files
                        $FileDir = WP_CONTENT_DIR . '/user-uploads/';
                        if (!file_exists($FileDir . $post_id)){
                            wp_mkdir_p($FileDir . $post_id);
                        }
                        for($i=0;$i<$FilesCount;$i++){
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
    <div class="Wrapper NowyWpis">
        <?php
            $Watki = get_terms(array(
                'taxonomy' => 'watki',
                'hide_empty' => false,
            ));
        ?>
        <div id="NewPostForm">
            <form method="POST" enctype='multipart/form-data'>
                <h5>Dodaj nowy wpis:
                    <span>
                        <select id="term_id" name="term_id">
                            <?php foreach($Watki as $Watek): ?>
                                <option value="<?= $Watek->term_id; ?>" <?php if($Watek->term_id == $term_id){echo('selected');}?>><?= $Watek->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                </h5>
                <button type="button" class="BackButton" onclick="goBackDefault()">Powrót</button>
                <input id="NewPostTitle" type="text" name="NewPostTitle" minlength="8" maxlength="64" placeholder="Dodaj tytuł Twojego wpisu..." />
                <textarea id="NewPostContent" type="text" name="NewPostContent" minlength="16" maxlength="256" placeholder="Dodaj treść Twojego wpisu..."></textarea>
                <input type="file" id="files" name="files[]" multiple/>
                <input type="submit" value="Dodaj post"/>
            </form>
        </div>
    </div>
<?php get_footer(); ?>