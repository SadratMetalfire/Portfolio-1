<?php
/**
 * Template Name: Ogłoszenie 
 * Template Post Type: ogloszenie
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
    if(isset($_SESSION['Wspolnota'])){
        $Użytkownik = $_SESSION['Uzytkownik'];
        $Wspolnota = $_SESSION['Wspolnota'];
        $Nazwa = $_SESSION["Nazwa"];
    }
?>
<?php 
    if($_SERVER['HTTP_REFERER'] == ("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])){
        $_SESSION['PrevUrl'];
    }else{
        $_SESSION['PrevUrl'] = $_SERVER['HTTP_REFERER'];
    }
?>
<?php
    get_header();
?>
<main id="site-content" role="main">
    <div class="Wrapper Post">
        <?php if(!isset($_GET['PrevUrl'])): ?>
            <button class="BackButton" onclick="goBack('<?= $_SESSION['PrevUrl'] ?>');">Powrót</button>
        <?php endif; ?>
        <div class="PostInner">
            <!-- Post content -->
            <div class="PostHeader">
                <div class="PostTitle">
                    <h2><?= the_title();?></h2>
                </div>
                <div class="PostInfo">
                    <p><span>Data:</span> <?= get_field("data_wpisu"); ?></p>
                </div>
            </div>
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <div class="PostContent">
                        <?= the_content(); ?>
                    </div>
                    <?php if(have_rows("zalaczniki")): ?>
                        <div class="PostAttachments">
                            <p class="AttachmentsHeader">Załączniki: </p>
                            <div class="Files">
                                <?php while(have_rows("zalaczniki")): the_row(); ?>
                                    <div class="File">
                                        <a href="<?= "../../wp-content/user-uploads/" . get_the_ID() . "/" . get_sub_field("nazwa_pliku"); ?>" download>
                                            <img src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconAttachment.svg" alt="" />
                                            <p class="FileName"><?= get_sub_field("nazwa_pliku"); ?></p>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</main><!-- #site-content -->
<?php get_footer(); ?>
<script>
    function goBack(url){
        window.location.href = url;
    }
</script>