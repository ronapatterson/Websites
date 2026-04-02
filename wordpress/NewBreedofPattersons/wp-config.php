<?php
/**
 * WordPress configuration for New Breed of Pattersons
 *
 * @package WordPress
 */

// ** Database settings ** //
define( 'DB_NAME', 'newbreedofpattersons' );
define( 'DB_USER', 'nbop_user' );
define( 'DB_PASSWORD', 'NBOP_Str0ng_P@ss2026' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 */
define('AUTH_KEY',         '<BO)11Q6~657oeNdF:wCAFT+m5aFVM=#h]p3(}-y4(|/dvOR[.d]8TtdA8l3Ac:G');
define('SECURE_AUTH_KEY',  'ORDN!2+L!^VbYdI-I{Um4QZ7uzk.O~FJ6y72/m7^6)AaN]+D.C[%O4y 8K|~Nout');
define('LOGGED_IN_KEY',    'Hu>soee<H>]+q)y$jyAF_{(Mb+>/ePs@):)mi4JS%u|Eu|(*t[~`-e}{g<|5gAPb');
define('NONCE_KEY',        ']!+|5eyoA;;yUZPCq<so)Sx{S3h3{Wxt:+-`v^yjmx 3oTk+./TuKU*Iyf%_iet3');
define('AUTH_SALT',        ';r&5[O6Th>o-{f$.-ld#7Sn>xu-AhNDm%8d_;D~}A0/{=bn2sv*M=8[c+_KZths(');
define('SECURE_AUTH_SALT', '~)@mbTE*@pw@=(gwVf6|-dD8BbV3-x3@Jq&,Y-fEysoLBm%8{}qF{)jZZ}_6ECU>');
define('LOGGED_IN_SALT',   'e3KT^!}|8eX:aAiLXlG6rPEp aI5Y[d;Sq[X[zfGEgfs.Ej;ynu5Nx[.SFKUpOaS');
define('NONCE_SALT',       '-qUNf]2moq}0;%k(D-YkdR+b9M,z%.qB/}|Y|uKBrJN;sOova0FQ^fFdz}kfV(1s');
/**#@-*/

$table_prefix = 'wp_';

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
