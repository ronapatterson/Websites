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
define( 'DB_NAME', 'ascendmen_wp' );

/** Database username */
define( 'DB_USER', 'wp_user' );

/** Database password */
define( 'DB_PASSWORD', 'wp_secure_pass_2024' );

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
define( 'AUTH_KEY',          'cj{M7VjIt-~Y)VVZ?|/m&?7$AlNkFPk_m)JQ?*|b.G3HKjM&!cOJ`XLU2%H80%tM' );
define( 'SECURE_AUTH_KEY',   'TRw3MuA%XEVn(gGFq-miQ_YfMKee]|.eU9(%H{ Vg80VA0kY{}!4+}NKjMS;S$ms' );
define( 'LOGGED_IN_KEY',     '_A6n*_ 9BohCo0cyTO7|b@*cQmOh9jpJHQLcB0eW6WWpPx1R>60K7#=eO,2>-~4p' );
define( 'NONCE_KEY',         'L 8?8@eBvynetH8QKmQ8vrkPxLY~|5]cbP765i{<o]bO[NcJ/@V`tOG=E@vyiLEz' );
define( 'AUTH_SALT',         '%-;e7X]Zg9~V.jje*M_qGUZ],UA> };fm|Wp~upMF#z[h_H/lH~DF)+r4kZVcB.y' );
define( 'SECURE_AUTH_SALT',  '$Q%@`*,U.z<e%%8wHs@F$wT)|Ubs{)=f2-c3La&bpwR;&Ap~i6vZ~#39fD0+4zK~' );
define( 'LOGGED_IN_SALT',    '+^]6Hn{z3s,Wkv>qXl2RGOOqN#1j3BeRs>Fy>D%xW]-9 CYbLZ]8)U^[t}`@@Fw/' );
define( 'NONCE_SALT',        'nfso,~GIF[igV+KuIlz#&k&.m7qZJ?P0HocK*tr)1ERTTz3m~YRzi?&:*=a)7J-`' );
define( 'WP_CACHE_KEY_SALT', 'WHj^K+;y@C-ulm:>[,i`)V@;NmuJHj8B,T`)s(%3Ljt+FyXkU)s+=ymXA1zE&Nx{' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'am_';


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
	define( 'WP_DEBUG', true );
}

define( 'FS_METHOD', 'direct' );

define( 'DISALLOW_FILE_EDIT', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
