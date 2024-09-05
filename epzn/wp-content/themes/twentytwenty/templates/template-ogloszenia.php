<?php
/**
 * Template Name: Ogłoszenia
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
    get_header();
?>
<main id="site-content" role="main">
    <div class="Wrapper Forum Watek Ogloszenia">
        <div class="ForumContent">
            <?php
                $Wpisy = get_posts(array(
                    'showposts' => -1,
                    'post_type' => 'ogloszenie',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'wspolnoty',
                            'field' => 'name',
                            'terms' => $Wspolnota
                        )
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
            ?>
            <div class="TopContainer">
                <h2 class="WatekName">
                    <?php echo($Watek->name); ?>
                </h2>
                <div class="ButtonsContainer">
                    <button class="BackButton" onclick="window.history.back();">Powrót</button>
                </div>
            </div>
            <?php $j = 0; foreach($Wpisy as $Wpis): $j++;?>
                <div class="Post">
                    <a href="<?= get_permalink($Wpis); ?>">
                        <div class="PostIconContainer">
                         <img src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconForum.svg" alt=""/>
                        </div>
                        <div class="PostContentContainer" style="width:925px;">
                            <p class="PostTitle">
                                <?= $Wpis->post_title; ?>
                            </p>
                            <p class="PostContent">
                                <?= substr(wp_strip_all_tags($Wpis->post_content),0,90); ?>...
                            </p>
                        </div>
                        <div class="PostInfo">
                            <p><span>Data:</span> <?= get_field("data_wpisu",$Wpis); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main><!-- #site-content -->
<?php get_footer(); ?>