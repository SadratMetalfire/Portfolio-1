<?php
/**
 * Template Name: Strona Główna
 * Template Post Type: page
 */
?>
<?php
    session_start();
?>
<?php
    if(isset($_SESSION["Wspolnota"])){
        header('Location:'.'http://e-pzn.pl/zalogowany/');
        die;
    }
?>
<?php
get_header();
?>
<main id="site-content" role="main">
        <div class="Wrapper HomePage">
            <div class="Columns3Wrapper">
                <div class="ColumnOuter">
                    <div class="ColumnInner">
                        <div class="DaneAdresowe">
                            <img class="HeaderIcon" src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconAdres.svg" alt=""/>
                            <h5 class="DaneHeader PoppinsBold">Dane adresowe</h5>
                            <p class="PoppinsBold">Partner Zarządzanie Nieruchomości Sp. z o.o.</p>
                            <p>Ul. Rotmistrza Witolda Pileckiego 26</p>
                            <p>62-400 Słupca</p>
                            <p>NIP 6671765158</p>
                        </div>
                    </div>
                </div>
                <div class="ColumnOuter">
                    <div class="ColumnInner">
                        <div class="DaneKontaktowe">
                            <img class="HeaderIcon" src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconMail.svg" alt=""/>
                            <h5 class="DaneHeader PoppinsBold">Dane kontaktowe</h5>
                            <p><span class="PoppinsBold">Email:</span> <a href="mailto:ebok@e-pzn.pl">ebok@e-pzn.pl</a></p>
                            <p><span class="PoppinsBold">Telefon:</span> <a href="tel:632758919">63 275 89 19</a></p>
                            <p><span class="PoppinsBold">Godziny urzędowania:</span> 7.00-15.00</p>
                        </div>
                    </div>
                </div>
                <div class="ColumnOuter">
                    <div class="ColumnInner">
                        <div class="LoginWrapper">
                            <?php if(isset($_SESSION["Wspolnota"])): ?>
                                <a class="LogoutButton" href="//e-pzn.pl/logout/">Wyloguj</a>
                            <?php else: ?>
                                <div class="LoginForm">
                                    <h4>Logowanie do panelu</span></h4>
                                    <form method="POST">
                                        <input id="Login" name="Login" type="text" placeholder="Login" />
                                        <input id="Password" name="Password" type="password" placeholder="Hasło" />
                                        <input type="submit" value="Zaloguj" />
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div><!-- .post-inner -->
	<div class="section-inner">
		<?php
		    edit_post_link();
		?>
	</div><!-- .section-inner -->
</main><!-- #site-content -->
<?php get_footer(); ?>