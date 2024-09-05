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
    if(isset($_POST['CommentContent'])){
        if(strlen($_POST['CommentContent']) >= 16){       
            // Set ACF row with comment
            $row = array(
                'autor_komentarza' => $Nazwa,
                'data_komentarza' => current_time("Y-m-d H:i:s"),
                'tresc_komentarza' => $_POST['CommentContent']
            );
            add_row('komentarze',$row,get_the_ID());
            // Redirect after submiting
            // TODO change to https when we will have SSL
            header('Location:'.'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            die;
        }
    }else if(isset($_POST['AnswerContent'])){
        if(strlen($_POST['AnswerContent']) >= 16){       
            // Set ACF row with comment
            update_field('autor_odpowiedzi','sebastian4usolution');
            update_field('data_odpowiedzi',current_time("Y-m-d H:i:s"));
            update_field('odpowiedz',$_POST['AnswerContent']);
            // Redirect after submiting
            // TODO change to https when we will have SSL
            header('Location:'.'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            die;
        }
    }
?>
<?php
    get_header();
?>
<main id="site-content" style="width:50%;margin:auto;" role="main">
    <!-- Post content -->
    <div class="PostHeader" style="text-align:center;">
        <h2><?= the_title();?></h2>
    </div>
    <?php if (have_posts()): ?>
        <?php while (have_posts()): the_post(); ?>
            <div class="PostContent">
                <?= the_content(); ?>
            </div>
            <div class="PostAnswer" style="margin-bottom:50px;">
                <h4 style="text-align:center;">Odpowiedź zarządcy:</h4>
                <?php if(get_field("odpowiedz") != ""): ?>
                    <div class="Answer" style="border:1px solid black;margin-bottom:50px;">
                        <div clas="AnswerInfo" style="border-bottom: 1px solid black;height:25px;position:relative;">
                            <p style="position:absolute;left:0px;top:0px;">Autor: <?= get_field("autor_odpowiedzi"); ?></p>
                            <p style="position:absolute;right:0px;top:0px;">Data: <?= get_field("data_odpowiedzi"); ?></p>
                        </div>
                        <div class="AnswerContent">
                            <p><?= get_field("odpowiedz"); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if($Użytkownik == "admin"): ?>
                        <div id="AnswerForm">
                            <h5 style="text-align:center;">Odpowiedz:</h5>
                            <form method="POST">
                                <input id="AnswerContent" name="AnswerContent" type="text" minlength="16" maxlength="256" placeholder="Treść odpowiedzi"/>
                                <input type="submit" value="Odpowiedz"/>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <button id="ButtonComment" onclick="ShowCommentForm()" style="margin:auto;display:block;">Dodaj komentarz</button>
            <div id="CommentForm" style="display:none;">
                <h5 style="text-align:center;">Dodaj komentarz:</h5>
                <form method="POST">
                    <input id="CommentContent" name="CommentContent" type="text" minlength="16" maxlength="256" placeholder="Treść komentarza"/>
                    <input type="submit" value="Dodaj komentarz"/>
                </form>
            </div>
            <div class="PostComments">
                <h5 style="text-align:center;">Komentarze:</h5>
                <?php if(have_rows("komentarze")): ?>
                    <?php while(have_rows("komentarze")): the_row(); ?>
                        <div class="Comment" style="border:1px solid black;margin-bottom:50px;">
                            <div clas="CommentInfo" style="border-bottom: 1px solid black;height:25px;position:relative;">
                                <p style="position:absolute;left:0px;top:0px;">Autor: <?= the_sub_field("autor_komentarza"); ?></p>
                                <p style="position:absolute;right:0px;top:0px;">Data: <?= the_sub_field("data_komentarza"); ?></p>
                            </div>
                            <div class="CommentContent">
                                <?= the_sub_field("tresc_komentarza"); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</main><!-- #site-content -->
<?php get_footer(); ?>
<script>
    function ShowCommentForm(){
        document.getElementById("CommentForm").style.display = "block";
        document.getElementById("ButtonComment").style.display = "none";
    }
</script>