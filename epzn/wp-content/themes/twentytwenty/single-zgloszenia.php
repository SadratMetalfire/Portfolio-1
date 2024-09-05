<?php
/**
 * Template Name: Wpis na forum
 * Template Post Type: smz
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
    if(isset($_POST['CommentContent'])){
        if(strlen($_POST['CommentContent']) >= 16){       
            // Set ACF row with comment
            $Odpowiedz = 0;
            if($Użytkownik == "Nadzorca"){
                $Odpowiedz = 1;
            }
            $row = array(
                'autor_komentarza' => $Nazwa,
                'data_komentarza' => current_time("Y-m-d H:i:s"),
                'tresc_komentarza' => $_POST['CommentContent'],
                'odpowiedz_zarzadcy' => $Odpowiedz
            );
            add_row('komentarze',$row,get_the_ID());
            // Redirect after submiting
            // TODO change to https when we will have SSL
            header('Location:'.'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            die;
        }
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
    if(isset($_POST['StatusValue'])){
        update_field('status',sanitize_text_field($_POST['StatusValue']));
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
            <div class="PostHeader ZgloszenieHeader">
                <div class="PostTitle">
                    <h2><?= the_title();?></h2>
                    <div class="ZgloszenieStatusContainer">
                        <?php if($Użytkownik == "Administrator" || $Użytkownik == "Nadzorca"): ?>
                            <select style="color:<?php $Status = get_field('status',$Zgłoszenie); if($Status == "Nowe"){echo("#FF2F2F");}else if($Status == "W trakcie"){echo("#FFA456");}else if($Status == "Zakończone"){echo("#009300");} ?>;border-color:<?php $Status = get_field('status',$Zgłoszenie); if($Status == "Nowe"){echo("#FF2F2F");}else if($Status == "W trakcie"){echo("#FFA456");}else if($Status == "Zakończone"){echo("#009300");} ?>;" id="StatusSelect">
                                <option style="color:#FF2F2F;" <?php if($Status == "Nowe"){echo("selected");} ?>>Nowe</option>
                                <option style="color:#FFA456;" <?php if($Status == "W trakcie"){echo("selected");} ?>>W trakcie</option>
                                <option style="color:#009300;" <?php if($Status == "Zakończone"){echo("selected");} ?>>Zakończone</option>
                            </select>
                            <form method="POST">
                                <input id="StatusValue" type="text" name="StatusValue"/>
                                <input id="UpdateStatusButton" type="submit" value="ZAPISZ STATUS" />
                            </form>
                        <?php else: ?>
                            <p style="color:<?php $Status = get_field('status',$Zgłoszenie); if($Status == "Nowe"){echo("#FF2F2F");}else if($Status == "W trakcie"){echo("#FFA456");}else if($Status == "Zakończone"){echo("#009300");} ?>;">
                                <?= $Status; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="PostInfo">
                    <p class="Autor"><span class="AutorTooltip"><?= get_field("autor_name"); ?></span><span>Autor:</span> <?= substr(get_field("autor_name"),0,13); ?>...</p>
                    <p><span>Data:</span> <?= get_field("data"); ?></p>
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
                    <div class="PostComments">
                        <?php if(have_rows("komentarze")): ?>
                            <?php while(have_rows("komentarze")): the_row(); ?>
                                <div class="Comment">
                                    <div class="CommentInfo">
                                        <?php if(get_sub_field('odpowiedz_zarzadcy')): ?>
                                            <p class="OdpowiedzZarzadcy">Odpowiedź zarządcy</p>
                                        <?php endif; ?>
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
        </div>
        <?php if(get_field('status') != "Zakończone"): ?>
            <div class="CommentFormWrapper">
                <form method="POST">
                    <input id="CommentContent" name="CommentContent" type="text" minlength="16" maxlength="256" placeholder="Napisz tutaj, aby odpowiedzieć..."/>
                    <input type="submit" value="Odpowiedz"/>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main><!-- #site-content -->
<?php get_footer(); ?>
<script>
    function goBack(url){
        window.location.href = url;
    }
</script>
<script>
    var StatusSelect = document.getElementById('StatusSelect');
    var OrginalStatus = StatusSelect.value;
    var StatusFormValue = document.getElementById("StatusValue");
    var UpdateStatusButton = document.getElementById("UpdateStatusButton");
    StatusSelect.onchange = function(){
        if(StatusSelect.value == "Nowe"){
            StatusSelect.style.color = "#FF2F2F";
            StatusSelect.style.borderColor = "#FF2F2F";
        }else if(StatusSelect.value == "W trakcie"){
            StatusSelect.style.color = "#FFA456";
            StatusSelect.style.borderColor = "#FFA456";
        }else if(StatusSelect.value == "Zakończone"){
            StatusSelect.style.color = "#009300";
            StatusSelect.style.borderColor = "#009300";
        }

        if(StatusSelect.value != OrginalStatus){
            UpdateStatusButton.style.display = "inherit";
            StatusFormValue.value = StatusSelect.value;
        }else{
            UpdateStatusButton.style.display = "none"
        }
    }
</script>