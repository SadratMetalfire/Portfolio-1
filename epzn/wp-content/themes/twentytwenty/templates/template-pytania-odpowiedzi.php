<?php
/**
 * Template Name: Pytania i odpowiedzi
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
    <div class="Wrapper PytaniaOdpowiedzi">
        <?php
            $Kategorie = get_terms(array(
                'taxonomy' => 'kategorie_faq',
                'hide_empty' => false,
            ));
        ?>
        <?php $i = 0; foreach($Kategorie as $Kategoria): ?>
            <?php
                $Wpisy = get_posts(array(
                    'showposts' => -1,
                    'post_type' => 'pytania_odpowiedzi',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'wspolnoty',
                            'field' => 'name',
                            'terms' => $Wspolnota
                        ),
                        array(
                            'taxonomy' => 'kategorie_faq',
                            'field' => 'name',
                            'terms' => $Kategoria
                        )
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
            ?>
            <div class="All">
                <div class="TopContainer">
                    <h2 class="CategoryName">
                        <?php echo($Kategoria->name); ?>
                    </h2>
                </div>
                <?php foreach($Wpisy as $Wpis): $i++;?>
                    <div class="Single">
                        <div class="Question">
                            <div class="Header">
                                <p><?= $Wpis->post_title; ?></p>
                            </div>
                            <div class="IconContainer">
                                <img class="QuestionArrow" src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconArrowUp.svg" alt="" onclick="ShowAnswer(<?= $i - 1; ?>);"/>
                            </div>
                        </div>
                        <div class="Answer">
                            <p><?= get_field('odpowiedz',$Wpis); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main><!-- #site-content -->
<?php get_footer(); ?>
<script>
    var Arrows = document.getElementsByClassName("QuestionArrow");
    var Elements = document.getElementsByClassName("Single");

    function StartUp(){
        for(var i = 0; i < Elements.length ; i++){
            var Closed = Elements[i].childNodes[1].offsetHeight + 60;
            var Opened = Closed + Elements[i].childNodes[3].offsetHeight + 30;
            Elements[i].setAttribute("closed",Closed + "px");
            Elements[i].setAttribute("opened",Opened + "px");
            Elements[i].style.maxHeight = Elements[i].getAttribute("closed");
            Elements[i].childNodes[3].style.opacity = 1;
        }
    }

    function ShowAnswer(id){
        var Arrow = Arrows[id];
        var Element = Elements[id];

        if(Arrow.style.transform == "rotate(180deg)"){
            Arrow.style.transform = "rotate(0deg)";
            Element.style.maxHeight = Element.getAttribute("closed");
        }else{
            Arrow.style.transform = "rotate(180deg)";
            Element.style.maxHeight = Element.getAttribute("opened");
        }
    }

    window.onload = function() {
        StartUp();
    };
</script>