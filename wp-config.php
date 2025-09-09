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
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'wordpress' );

/** Database hostname */
define( 'DB_HOST', 'database' );

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
define( 'AUTH_KEY',         '6p,X>s<%O6}hn~rw?yJi]JpZg%;4z )%7OaEY_`+@]!}J[I{/%c_|MUE/UVr#~X6' );
define( 'SECURE_AUTH_KEY',  'Fn)z!oAolk`kYF+GJza~Qg<}]F]8KpHyfasn J&q!a<[_/D/KPiNvka<htRH~fK$' );
define( 'LOGGED_IN_KEY',    'Q +JwH0=cCs#O-qU$%LF3JY/ZdLk543>zD&?Q3T}WEF_?M!vrb;S)1!:]{h[7-{H' );
define( 'NONCE_KEY',        'tF2I!i-8Fu $o*{=A<PUUkk5c5~;kJ6(jNqaAL[kK=w~$*Q`O6&5%YKbE>{09.CN' );
define( 'AUTH_SALT',        'd)bn92pW et5%_yy_@<+wD`ga|zYz8Z--1@i-XY9><Lx$i0LG~k9}5_k~1jalxxz' );
define( 'SECURE_AUTH_SALT', 'Yc^u<OZ>=ao*qaS,g/t2gVcKG>P7)0[S4>!x![>[1^Ce-(sln]1!{m1dWCp8r-d)' );
define( 'LOGGED_IN_SALT',   ']=a{r0/hz?H*Tu]}U=B<O*sclbi1rSFlK{E$-@`sb|C}&hHc8lB?akj7H;_#9Q,:' );
define( 'NONCE_SALT',       '.vw>rHxAI[`N|-W?5kT,&_0w%]A1gb~@5IM6g-+`ud.,;DabX:kByg0Y*KpcCKi/' );

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
