<?php
/**
 * Template Name: Documents
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
    <div class="Wrapper Documents">
        <?php
            $Dokumenty = get_posts(array(
                'showposts' => -1,
                'post_type' => 'dokumenty',
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
        <div class="AllDocuments">
            <?php for($i = 0; $i < 1 ; $i++): ?>
                <div class="TopContainer">
                    <?php if($i == 0): $Type = "Uchwała"; ?>
                        <h2 class="TypeName">Uchwały</h2>
                    <?php endif; ?>
                </div>
                <?php $j = 0; foreach($Dokumenty as $Dokument): $j++;?>
                    <?php if(get_field('typename',$Dokument) == $Type): ?>
                        <div class="DocumentSingle">
                            <a class="DocumentLink" href="//e-pzn.pl/wp-content/site_data/documents/<?= get_field('filename',$Dokument); ?>" target="_blank">
                                <div class="DocumentIconContainer">
                                    <img src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconDocuments.svg" alt=""/>
                                </div>
                                <div class="DocumentContentContainer">
                                    <p class="DocumentTitle">
                                        <?= $Dokument->post_title; ?>
                                    </p>
                                </div>
                                <div class="DocumentInfo">
                                    <p><span>Data:</span> <?= get_field("year",$Dokument); ?>-<?= get_field("month",$Dokument); ?></p>
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