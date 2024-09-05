<?php
/**
 * Template Name: Rozliczenia
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
    <div class="Wrapper Rozliczenia">
        <?php
            $Konto = get_posts(array(
                'name' => $UzytkownikId,
                'post_type' => 'konto',
                'showposts' => 1,
            ));
        ?>
        <?php
            $Rozliczenia = get_posts(array(
                'showposts' => -1,
                'name' => $UzytkownikId,
                'post_type' => 'rozliczenia',
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
        <?php
            $Min = 9999; $Max = 0;
            foreach($Rozliczenia as $Rozliczenie){
                if(have_rows('inner',$Rozliczenie)){
                    while(have_rows('inner',$Rozliczenie)){
                        the_row();
                        if(get_sub_field('year') < $Min){
                            $Min = get_sub_field('year');
                        }else if(get_sub_field('year') > $Max){
                            $Max = get_sub_field('year');
                        }
                    }
                }
            }
        ?>
        <div class="Panel">
            <?php  foreach($Rozliczenia as $Rozliczenie): ?>
                <div class="SelectYearContainer">
                    <p>Wybierz rok:</p>
                    <select id="SelectYear" onchange="DateChanged();">
                        <option selected>Wszystkie</option>
                        <?php for(;$Min <= $Max ; $Min++): ?>
                            <option value="<?= $Min; ?>"><?= $Min; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="SelectMonthContainer">
                    <p>Wybierz miesiąc:</p>
                    <select id="SelectMonth" onchange="DateChanged();">
                        <option selected>Wszystkie</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="SelectLocalContainer">
                    <p>Wybierz lokal:</p>
                    <?php if(have_rows("zzresourcename",$Konto[0])): ?>
                        <select id="SelectLocal">
                            <?php $i = 0; while(have_rows("zzresourcename",$Konto[0])): the_row(); $i++;?>
                                <option value="<?= get_sub_field("pietro"); ?>:<?= get_sub_field("nrmieszkania"); ?>" <?php if($i == 1){echo("selected");} ?>><?= get_sub_field("pietro"); ?>:<?= get_sub_field("nrmieszkania"); ?></option>
                            <?php endwhile; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="AdressContainer">
                    <p>Adres: <?= get_field("zzadress",$Konto[0]); ?></p>
                </div>
                <div class="NameContainer">
                    <p>Imie nazwisko: <?= get_field("zzcustomername",$Konto[0]); ?></p>
                </div>
                <div class="PhoneContainer">
                    <p>Telefon: <?= get_field("gsmtel",$Konto[0]); ?></p>
                </div>
                <div class="EmailContainer">
                    <p>Email: <?= get_field("email",$Konto[0]); ?></p>
                </div>
                <div class="LoginContainer">
                    <p>Login: <?= get_field("login",$Konto[0]); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <table class="Rozliczenia">
            <thead>
                <tr>
                    <th>Rok</th>
                    <th>Miesiąc</th>
                    <th>Zobowiązanie</th>
                    <th>Zapłacono</th>
                    <th>Do zapłaty</th>
                    <th>Nadpłata</th>
                </tr>
            </thead>
            <tbody>
                <?php $j = 0; foreach($Rozliczenia as $Rozliczenie): $j++;?>
                    <?php if(have_rows('inner',$Rozliczenie)): ?>
                        <?php while(have_rows('inner',$Rozliczenie)): the_row(); ?>
                            <tr class="TableRow">
                                <td><?= get_sub_field('year'); ?></td>
                                <td><?= get_sub_field('month'); ?></td>
                                <td><?= get_sub_field('liability'); ?></td>
                                <td><?= get_sub_field('paid'); ?></td>
                                <td><?= get_sub_field('topay'); ?></td>
                                <td><?= get_sub_field('overpayment'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main><!-- #site-content -->
<script>
    var SelectYear = document.getElementById("SelectYear");
    var SelectMonth = document.getElementById("SelectMonth");

    var TableRows = document.getElementsByClassName("TableRow");

    function DateChanged(){
        for(var i = 0; i < TableRows.length ; i++){
            TableRows[i].style.display = "none";
            if(SelectYear.value == "Wszystkie"){
                if(SelectMonth.value == "Wszystkie"){
                    TableRows[i].style.display = "";
                }else if(SelectMonth.value == TableRows[i].children[1].innerHTML){
                    TableRows[i].style.display = "";
                }
            }else{
                if(SelectYear.value == TableRows[i].children[0].innerHTML){
                    if(SelectMonth.value == "Wszystkie"){
                        TableRows[i].style.display = "";
                    }else if(SelectMonth.value == TableRows[i].children[1].innerHTML){
                        TableRows[i].style.display = "";
                    }
                }
            }
        }
    }

    window.onload = DateChanged();
</script>
<?php get_footer(); ?>