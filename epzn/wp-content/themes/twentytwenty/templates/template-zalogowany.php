<?php
/*
 * Template Name: Zalogowany
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
    $Nazwa = $_SESSION["Nazwa"];
?>
<?php
    require_once( ABSPATH . 'wp-admin/includes/post.php' );
    $args = array(
        'posts_per_page' => -1,
        'post_type' => array('konto'),
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'zzcustomername',
                'value' => $Nazwa,
                'compare' => '='
            ),
        )
    );
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $Login = get_field('login');
            $Email = get_field('email');
            $Adress = get_field('zzadress');
            $Mieszkania = "";
            if( have_rows('zzresourcename') ){
                $i = 0;
                while (have_rows('zzresourcename')){
                    the_row();
                    if($i != 0){
                        $Mieszkania = ',' . $Mieszkania . get_sub_field('pietro') . '/' . get_sub_field('nrmieszkania');
                    }else{
                        $Mieszkania = $Mieszkania . get_sub_field('pietro') . '/' . get_sub_field('nrmieszkania');
                    }
                    $i++;
                }
            }
        }
    }
?>
<?php
    get_header();
?>
<main id="site-content" role="main">
    <div class="Wrapper Zalogowany">
        <div class="OgloszeniaContainer">
            <?php
                $Posts = get_posts(array(
                'posts_per_page' => 2,
                'post_type' => 'ogloszenie',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'wspolnoty',
                        'field' => 'name',
                        'terms' => $Wspolnota
                    )
                ),
                'orderby' => 'date',
                'order' => 'DESC'));
            ?>
            <div class="TopContainer">
                <h2><a href="http://e-pzn.pl/ogloszenia/">Ogłoszenia</a></h2>
                <div class="ButtonsContainer">
                    <button class="SeeMoreButton" onclick="window.location.href = '//e-pzn.pl/ogloszenia/';">Zobacz więcej</button>
                </div>
            </div>
            <div class="OgloszeniaInner">
                <?php foreach($Posts as $Post): ?> 
                    <a href="<?= get_permalink($Post); ?>">
                        <div class="Ogloszenie">
                            <div class="DateContainer">
                                <p><span>Data:</span> <?= get_field('data_wpisu',$Post); ?></p>
                            </div>
                            <div class="OgloszenieContent">
                                <?= $Post->post_content; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="DataContainer">
            <div class="DataInner">
                <div class="DataColumn">
                    <div class="DataColumnInner">
                        <img class="DataIcon" src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconData.svg" alt=""/>
                        <h5>Twoje dane</h5>
                        <p><span>Login: </span><?= $Login; ?></p>
                        <p><span>Numer mieszkania: </span><?= $Mieszkania; ?></p>
                        <p><span>Imię i nazwisko: </span><?= $Nazwa; ?></p>
                        <p><span>Adres: </span><?= $Adress; ?></p>
                        <p><span>Email: </span><?= $Email; ?></p>
                    </div>
                </div>
                <div class="DataColumn">
                    <div class="DataColumnInner">
                        <img class="DataIcon" src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconAdres.svg" alt=""/>
                        <h5>Dane adresowe</h5>
                        <p class="PoppinsBold">Partner Zarządzanie Nieruchomości Sp. z o.o.</p>
                        <p>Ul. Rotmistrza Witolda Pileckiego 26</p>
                        <p>62-400 Słupca</p>
                        <p>NIP 6671765158</p>
                    </div>
                </div>
                <div class="DataColumn">
                    <div class="DataColumnInner">
                        <img class="DataIcon" src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconMail.svg" alt=""/>
                        <h5>Dane kontaktowe</h5>
                        <p><span class="PoppinsBold">Email:</span> <a href="mailto:ebok@e-pzn.pl">ebok@e-pzn.pl</a></p>
                        <p><span class="PoppinsBold">Telefon:</span> <a href="tel:632758919">63 275 89 19</a></p>
                        <p><span class="PoppinsBold">Godziny urzędowania:</span> 7.00-15.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>
<script>
    document.getElementById("menu-item-1870").classList.add("current-menu-item");
</script>