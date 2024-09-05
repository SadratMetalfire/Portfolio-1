<?php
/**
 * Header file for the Twenty Twenty WordPress default theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

?>
<?php
session_start();
?>
<?php
	if(wp_get_current_user()->user_login){
		$WpUser = wp_get_current_user();
		if(strpos($WpUser->roles[0], 'administrator') !== false){
			$_SESSION["Wspolnota"] = "smz";
			$_SESSION["Uzytkownik"] = "Administrator";
			$_SESSION["UzytkownikID"] = "5576";
			$_SESSION["Nazwa"] = $WpUser->user_login;
		}else if(strpos($WpUser->roles[0], 'zarzadca') !== false){
			$_SESSION["Wspolnota"] = substr($WpUser->user_login, strpos($WpUser->user_login, "@") + 1);
			$_SESSION["Uzytkownik"] = "Nadzorca";
			$_SESSION["UzytkownikID"] = null;
			$_SESSION["Nazwa"] = $WpUser->user_login;
		}
	}
?>
<?php
    if(isset($_POST['Login'])){
		if(username_exists($_POST['Login'])){
			$user = get_user_by('login',$_POST['Login']);
			if(wp_check_password($_POST['Password'],$user->user_pass)){
				$userCredentials = array(
					'user_login' => $user->user_login,
					'user_password' => $_POST['Password'],
					'remember' => false
				);
				$user = wp_signon($userCredentials, true);
				if ( is_wp_error( $user ) ) {
					echo $user->get_error_message();
				}else{
					wp_set_current_user( $user->ID, $user->user_login );
					wp_set_auth_cookie( $user->ID, false, is_ssl() );
					do_action( 'wp_login', $user->user_login );
					if(strpos($user->roles[0], 'administrator') !== false){
						$_SESSION["Wspolnota"] = "smz";
						$_SESSION["Uzytkownik"] = "Administrator";
						$_SESSION["UzytkownikID"] = "5576";
						$_SESSION["Nazwa"] = $user->user_login;
					}else if(strpos($user->roles[0], 'zarzadca') !== false){
						$_SESSION["Wspolnota"] = substr($user->user_login, strpos($user->user_login, "@") + 1);
						$_SESSION["Uzytkownik"] = "Nadzorca";
						$_SESSION["UzytkownikID"] = null;
						$_SESSION["Nazwa"] = $user->user_login;
					}
					header('Location:'. 'http://e-pzn.pl/forum/');
					die;
				}
			}
		}else{
			require_once( ABSPATH . 'wp-admin/includes/post.php' );
			$Accounts = get_posts(array(
				'posts_per_page' => 1,
				'post_type' => 'konto',
				'meta_key' => 'login',
				'meta_value' => $_POST['Login'],
			));
			if ( $Accounts[0] != NULL ) {
				if(get_field('active',$Accounts[0])){
					if(password_verify($_POST['Password'],get_field('haslo',$Accounts[0]))){
						$Uzytkownik = get_field('typ_konta',$Accounts[0]);
						$UzytkownikId = $Accounts[0]->post_title;
						$Wspolnota = get_the_terms($Accounts[0],'wspolnoty')[0]->name;
						$Nazwa = get_field('zzcustomername',$Accounts[0]);
						$_SESSION["Wspolnota"] = $Wspolnota;
						$_SESSION["Uzytkownik"] = $Uzytkownik;
						$_SESSION["UzytkownikID"] = $UzytkownikId;
						$_SESSION["Nazwa"] = $Nazwa;
						header('Location:'. 'http://e-pzn.pl/zalogowany/');
						die;
					}else{
						echo("Złe hasło");
					}
				}else{
					echo("Konto nie jest aktywne");
				}
			}else{
				echo("Brak konta");
			}
		}
    }
?>

<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >

		<?php wp_head(); ?>

		<link rel="profile" href="https://gmpg.org/xfn/11">
		<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
		<link href="<?= get_template_directory_uri() ?>/assets/css/scss/style.css" rel="stylesheet">

	</head>

	<body <?php body_class(); ?>>
	
		<?php
		wp_body_open();
		?>

		<header id="site-header" class="header-footer-group" role="banner">

		<div class="HeaderInner">
			<div class="HeaderImage <?php if(!isset($_SESSION["Wspolnota"])){echo("Centered");} ?>">
				<a href="//e-pzn.pl<?php if(isset($_SESSION["Wspolnota"])){echo("/zalogowany");}?>">
					<img src="<?= get_template_directory_uri(); ?>/assets/images/icons/Logo.svg" alt=""/>
				</a>
			</div>
			<!-- != '/' -->
			<?php if(isset($_SESSION["Wspolnota"])): ?>
				<div class="HeaderMenu">
					<?php wp_nav_menu(); ?>
					<?php if(isset($_SESSION["Wspolnota"])): ?>
						<a class="LogoutButton" href="//e-pzn.pl/logout/"><img src="<?= get_template_directory_uri(); ?>/assets/images/icons/IconLogout.svg" alt=""/></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		</header><!-- #site-header -->
		<?php
		// Output the menu modal.
		get_template_part( 'template-parts/modal-menu' );
