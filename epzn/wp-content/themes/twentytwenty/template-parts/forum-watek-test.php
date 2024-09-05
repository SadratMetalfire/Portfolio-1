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
    }else{
        $Watek = get_term($_POST['term_id']);
    }
    $Wspolnota = $_SESSION['Wspolnota']; 
?>
<?php
    get_header();
?>
<main id="site-content" role="main">
    <div class="Wrapper Forum Watek">
        <div class="ForumContent">
    <?php
        $Wpisy = get_posts(array(
            'post_type' => $Wspolnota,
            'numberposts' => -1,
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
    <div class="TopContainer">
        <h2 class="WatekName">
            <?php echo($Watek->name); ?>
        </h2>
        <div class="ButtonsContainer">
            <button class="NewWatekButton" onclick="window.location.href = '\/\/e-pzn.pl\/nowy-wpis\/?term_id=<?= $Watek->term_id?>';">Nowy wpis</button>
            <button class="BackButton" onclick="window.location.href = '\/\/e-pzn.pl\/forum\/'">Powrót</button>
        </div>
    </div>
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
</main><!-- #site-content -->
<?php get_footer(); ?>