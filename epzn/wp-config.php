<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

define('DISALLOW_FILE_EDIT',true);
require_once "wp-config-db-nkzkvdcv.php";

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'o5sy8b(rq-?6~VFz^!Al)-j,et=h$NhRYRI#GtH1+*f:uEK*X+giLJ$dke= jw6s' );
define( 'SECURE_AUTH_KEY',  '{]lX{zFWX{!.c**FVHZ]H(gQrQ??eDV+V}cm=dEm_=RA1^9>WOzR)949xcG?WyTA' );
define( 'LOGGED_IN_KEY',    'uq}>cd#*~JHiN*Zq,K6Q5garX}Y 6-E:c}_0?Dy>ntmdP]B0is^4|SDA[w&!?H@Z' );
define( 'NONCE_KEY',        '51E4W]M|nsb~+v9 zjavoe&A)*B*o(oaD-jZ!bDbL]|FZB%T&!}1LvX|[DisBad$' );
define( 'AUTH_SALT',        'c7FX]!=2+[>9mgo41Pet1&I?R.#,xtdaxiZZb:5atUcdIp5ZkIpsL[LT:hq`@&{ ' );
define( 'SECURE_AUTH_SALT', 'ddT_e8_uI#u1wTi1gG64ho@@2S@0{.8BKT_} rDqDFlS88;2y5oQ1o_3gh,=9Fa(' );
define( 'LOGGED_IN_SALT',   'B@08eb|@(j,ijfuphTs.[Xl)ujp,80:dXp]nu~iIZ*TS2B@St /4(z3Yx}},8yt!' );
define( 'NONCE_SALT',       'ayPH@L}p!YQ`4+7vTN{9CwwC<;*QV$%$KF(1V-3>M(3T:^ z~!icK5N)Az]#?.;5' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
