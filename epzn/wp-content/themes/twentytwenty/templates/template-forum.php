<?php
/**
 * Template Name: Forum
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
    if(isset($_SESSION['Wspolnota'])){
        $Uzytkownik = $_SESSION['Uzytkownik'];
        $Wspolnota = $_SESSION['Wspolnota'];
        $Nazwa = $_SESSION["Nazwa"];
    }
?>
<?php
    get_header();
?>
<main id="site-content" role="main">
    <div class="Wrapper Forum">
        <?php
            $Watki = get_terms(array(
                'taxonomy' => 'watki',
                'hide_empty' => false,
            ));
        ?>
        <?php $i = 0; foreach($Watki as $Watek): $i++;?>
            <?php if($Watek->name != "Ogłoszenia"): ?>
                <div class="ForumContent">
                    <div class="TopContainer">
                        <h2 class="WatekName">
                            <?php $WatekLink = get_term_link($Watek) . "?term_id=" . $Watek->term_id ?>
                            <a href="<?= $WatekLink; ?>">
                                <?php echo($Watek->name); ?>
                            </a>
                        </h2>
                        <div class="ButtonsContainer">
                            <button class="NewWatekButton" onclick="window.location.href = '\/\/e-pzn.pl\/nowy-wpis\/?term_id=<?= $Watek->term_id?>';">Nowy wpis</button>
                            <button class="SeeMoreButton" onclick="window.location.href = '<?= $WatekLink; ?>';">Zobacz więcej</button>
                        </div>
                    </div>
                    <?php
                        $Wpisy = get_posts(array(
                            'post_type' => $Wspolnota,
                            'numberposts' => 4,
                            'orderby' => 'date',
                            'order' => "DESC",
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'watki',
                                    'field' => 'term_id',
                                    'terms' => $Watek->term_id,
                                )
                            )
                        ));
                    ?>
                    <?php $j = 0; foreach($Wpisy as $Wpis): $j++;?>
                        <div class="Post">
                            <a href="<?= get_permalink($Wpis); ?>">
                                <div class="PostIconContainer">
                                    <img src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconForum.svg" alt=""/>
                                </div>
                                <div class="PostContentContainer">
                                    <p class="PostTitle">
                                        <?= $Wpis->post_title; ?>
                                    </p>
                                    <p class="PostContent">
                                        <?= substr(wp_strip_all_tags($Wpis->post_content),0,90); ?>...
                                    </p>
                                </div>
                                <div class="PostAnswersContainer">
                                    <p class="PostAnswers">
                                        <?php 
                                            $AnswersCount = 0;
                                            if(have_rows('komentarze',$Wpis)){
                                                while(have_rows('komentarze',$Wpis)){
                                                    the_row();
                                                    $AnswersCount++;
                                                }
                                            }
                                            if($AnswersCount == 1){
                                                echo($AnswersCount . " odpowiedź");
                                            }else{
                                                echo($AnswersCount . " odpowiedzi");
                                            }
                                        ?>
                                    </p>
                                </div>
                                <div class="PostInfo">
                                    <p class="Autor"><span class="AutorTooltip"><?= get_field("autor_wpisu",$Wpis); ?></span><span>Autor:</span> <?= substr(get_field("autor_wpisu",$Wpis),0,13); ?>...</p>
                                    <p><span>Data:</span> <?= get_field("data_wpisu",$Wpis); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</main><!-- #site-content -->
<?php get_footer(); ?>