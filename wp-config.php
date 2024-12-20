<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'Password@1122' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '1SBk1]9Ij`D$%]/(wsY]6j/p6*#4LXh|[YR.$8!vTAmv{ZP+~x,9x*}S}>etq/5Y' );
define( 'SECURE_AUTH_KEY',  'Apj0Tah<<B+!FN!#&Xea[|+mFN#B$k4Fw&sXO1<%eY<1oa&,KX:ZUd:?-~)Ofger' );
define( 'LOGGED_IN_KEY',    'QZsedA2LU<21()nv>gH{;d1J!yw>~?9uN?k9f*AjRU=C}{3/`%Nzij&?nSZ<duI3' );
define( 'NONCE_KEY',        'K5jQt#xx9-r1[Vu2ol/P{%:4Es9P7Aj35Wqo{h(A[0`p|LvY}V.-Jmgu~z]:[*  ' );
define( 'AUTH_SALT',        'wZ|_CMY^c.?Xuw?:G6dlu?SI/ly5D|z.9};,6ujd6iyF/v:7CY?aT*9[N a9Ce g' );
define( 'SECURE_AUTH_SALT', 'ahwgLxTmGZQAFAs]Rf8];a{$~A_=<YPRjYsCU(q1g`JJSyH%_OmJ)2W*u$:9($SO' );
define( 'LOGGED_IN_SALT',   '5YEn[<do^+R}[0zd@x.WVyASI:].rz:l?ft`9^wiyNc:vry$D%{?-(CqK&g~5Euy' );
define( 'NONCE_SALT',       ')t/:K$};e!>RV`PwBiX[+d;`.<sj-YGD6l|1/g.XV?y_^k+WZ3{eJO0#U^b]LHNM' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
