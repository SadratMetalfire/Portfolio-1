<?php
/**
 * Template Name: Zgłoszenia
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
        $UzytkownikId = $_SESSION['UzytkownikID'];
        $Wspolnota = $_SESSION['Wspolnota'];
        $Nazwa = $_SESSION["Nazwa"];
    }
?>
<?php
    get_header();
?>
<main id="site-content" role="main">
    <div class="Wrapper Zgloszenia">
        <?php
            $Zgłoszenia = get_posts(array(
                'showposts' => -1,
                'post_type' => 'zgloszenia',
                'meta_key' => 'autor',
                'meta_value' => $UzytkownikId,
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
        <div class="AllZgloszenia">
            <?php for($i = 0; $i < 1 ; $i++): ?>
                <div class="TopContainer">
                    <h2 class="TypeName">Zgłoszenia</h2>
                    <div class="ButtonsContainer">
                        <button class="NewZgloszenieButton" onclick="window.location.href = '\/\/e-pzn.pl\/nowe-zgloszenie\/';">Nowe zgłoszenie</button>
                    </div>
                </div>
                <?php $j = 0; foreach($Zgłoszenia as $Zgłoszenie): $j++;?>
                    <?php if(get_field('typename',$Zgłoszenie) == $Type): ?>
                        <div class="ZgloszenieSingle">
                            <a href="<?= get_permalink($Zgłoszenie); ?>">
                                <div class="ZgloszenieIconContainer">
                                    <img src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconMegaphone.svg" alt=""/>
                                </div>
                                <div class="ZgloszenieContentContainer">
                                    <p class="ZgloszenieTitle">
                                        <?= $Zgłoszenie->post_title; ?>
                                    </p>
                                </div>
                                <div class="ZgloszenieStatusContainer">
                                    <p style="color:<?php $Status = get_field('status',$Zgłoszenie); if($Status == "Nowe"){echo("#FF2F2F");}else if($Status == "W trakcie"){echo("#FFA456");}else if($Status == "Zakończone"){echo("#009300");} ?>;">
                                        <?= $Status; ?>
                                    </p>
                                </div>
                                <div class="ZgloszenieInfo">
                                <p class="Autor"><span class="AutorTooltip"><?= get_field("autor_name",$Zgłoszenie); ?></span><span>Autor:</span> <?= substr(get_field("autor_name",$Zgłoszenie),0,13); ?>...</p>
                                    <p><span>Data:</span><?= get_field("data",$Zgłoszenie); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
</main><!-- #site-content -->
<?php get_footer(); ?>