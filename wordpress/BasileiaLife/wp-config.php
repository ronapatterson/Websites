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
define( 'DB_NAME', 'basileia_wp' );

/** Database username */
define( 'DB_USER', 'basileia' );

/** Database password */
define( 'DB_PASSWORD', 'basileia_pass_2024' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'Up/^RZ~TKCxMQKm}|ZInO(z0lZ#pn_d[gnaM-Xw.o%zU$<lz>e0{bPa24U`!JC s' );
define( 'SECURE_AUTH_KEY',   '#17@DrK?X|;up^I`mqNncr/hFvL*z+rzo-lQyNB1Qeiva{PsLGm J4b[QpG$,$~+' );
define( 'LOGGED_IN_KEY',     'Byc:lA|DeMKM1YN6li8R85?,BI`=1]WeyNGx>QMT^ 7LRZD#G,<_k+{hE=Oz-!vn' );
define( 'NONCE_KEY',         '18hZyK5iqLtN?.E-E>`$(~q@9Ve}grFTp&0z}s >%{|Y`f-I~[rHquCL:kuoxc&G' );
define( 'AUTH_SALT',         'K/m,/N`xu->7_v:$F&@5[OnU%=.W@^u@[<n+0Uv+^AIxX5M8,XJE+C&e8B|gS6Tb' );
define( 'SECURE_AUTH_SALT',  '>iAgqbzP3X|*3Tql;T BGl#L%{wl5Cmn1lxj~$q}f1:#e*D42||N`#VsU~>Ddt-b' );
define( 'LOGGED_IN_SALT',    'AGlT>3yqzvGEV82x0o]V%1kwinA,&6TwakcRAuzxlKs&{YaCj__Zc^| eRv*pwH#' );
define( 'NONCE_SALT',        'a1~Eb5J6QF|-d7maPgay]t +u9>e42LL.hz0/hC8(~s^^ V@<RL^iG3mD=zyF_x`' );
define( 'WP_CACHE_KEY_SALT', 'oRCQ(o}G&&ND@DOsS-G/L)f!phB@IK>}TMD#L/C{?ocNX*7POK$`^mjOW=Ss0^[!' );


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
