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
    get_header();
?>
<main id="site-content" role="main">
    <?php
        $Watki = get_terms(array(
            'taxonomy' => $Wspolnota,
            'hide_empty' => false,
        ));
    ?>
    <?php $i = 0; foreach($Watki as $Watek): $i++;?>
        <div class="ForumContent" style="text-align:center;">
            <div class="Watek">
                <h2>Wątek: 
                    <a href="<?= get_term_link($Watek); ?>?term_id=<?= $Watek->term_id; ?>">
                        <?php echo($Watek->name); ?>
                    </a>
                </h2>
            </div>
            <?php
                $Wpisy = get_posts(array(
                    'post_type' => 'forum_wpis',
                    'numberposts' => 2,
                    'orderby' => 'date',
                    'order' => "DESC",
                    'tax_query' => array(
                        array(
                            'taxonomy' => $Wspolnota,
                            'field' => 'term_id',
                            'terms' => $Watek->term_id,
                        )
                    )
                ));
            ?>
            <?php $j = 0; foreach($Wpisy as $Wpis): $j++;?>
                <div class="Wpis" style="border:1px solid black;width:50%;display:block;margin:auto;margin-bottom:25px">
                    <div clas="WpisInfo" style="border-bottom: 1px solid black;height:25px;position:relative;">
                        <p style="position:absolute;left:0px;top:0px;">Autor: <?= get_field("autor_wpisu",$Wpis); ?></p>
                        <p style="position:absolute;right:0px;top:0px;">Data: <?= get_field("data_wpisu",$Wpis); ?></p>
                    </div>
                    <p style="margin-bottom:0px;"> Wpis:
                        <a href="<?= get_permalink($Wpis); ?>">
                            <?= $Wpis->post_title; ?>
                        </a>
                    </p>
                    <p style="margin-bottom:0px;"> Treść:
                        <?= substr(wp_strip_all_tags($Wpis->post_content),0,50); ?>...
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</main><!-- #site-content -->
<?php get_footer(); ?>