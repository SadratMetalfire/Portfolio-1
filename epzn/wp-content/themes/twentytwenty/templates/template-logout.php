<?php
/**
 * Template Name: Logout
 * Template Post Type: page
 */
?>
<?php
    session_start(); 
    if(wp_get_current_user()->user_login){
        wp_logout();
    }
    session_destroy();
    header('Location:' . 'http://e-pzn.pl');
?>