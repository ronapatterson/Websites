<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'perfectloverestored' );

/** Database username */
define( 'DB_USER', 'plr_user' );

/** Database password */
define( 'DB_PASSWORD', 'PLR_Str0ng_P@ss2026' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '35@)mc*)ZD~e,>8FUb?YXWEf+9Wi]9~ngnJ[)nn>S[_7}60mzqN@[^]mrBuu&*[v' );
define( 'SECURE_AUTH_KEY',   '!b^,G+ -Gd}Pw6ur_AvW0#Cp6,TuP8=$(e{VWQ7X=Q`O$XA s=r. Nd`apyEj-{8' );
define( 'LOGGED_IN_KEY',     'mA2fR5L`Q<_8GunA2=iErW6k&mu+VgDk3BM=gb%;fXz}h;!|UhJ*i,Fc_O`*{.*P' );
define( 'NONCE_KEY',         '^<w+dyl6Qh|^a--kI:<:MHo*:]>.hWM!p%a802{]PkJ{%Mga!|N9:k%(o@w?hS_b' );
define( 'AUTH_SALT',         'bo7/Kgau)h`E$,x<2RaEu3kE%kYyJu){gi!NRBqUxu1pu$H+#1q#i$]I5+ETbxS5' );
define( 'SECURE_AUTH_SALT',  '59nj6f4Gcy#Y_%Tv-@)]6QTT5vkBZL~v)I=)=QiM,J*=IDRl6u[)}p *B{xAc7+w' );
define( 'LOGGED_IN_SALT',    '=#=)XFJxnOSk,XUTo4PkeCas)j9>r4_MW1ogt:aMMMY7qlXEZO06uoa?^QH_S RT' );
define( 'NONCE_SALT',        'owE2emE1e4;~1N[pKT%rx[S*7vTS@#gJRQVI[bhoH4Is*WlMU}m8>AT46&av}fN+' );
define( 'WP_CACHE_KEY_SALT', ']kpI%^1Ti&zANu.&BTG=YpR@hWz?uz5n@fgq#R*]neG~8X5mVaU2fn@9z:~-4Kg8' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
