<?php
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/var/www/vhosts/forsage.40digit.ru/httpdocs/wp-content/plugins/wp-super-cache/' );
define('WP_AUTO_UPDATE_CORE', false);// Эта настройка требуется для того, чтобы убедиться, что обновлениями WordPress можно корректно управлять в WordPress Toolkit. Удалите эту строку, если этот экземпляр WordPress больше не управляется WordPress Toolkit.
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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_forsage' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
define( 'WP_HOME', 'http://forsagewp' );
define( 'WP_SITEURL', 'http://forsagewp' );
/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', ']DDZ%0PNLaCjlZr76475l4t@%61[]O:_h_[D%aJH39(%940P73cT1*F|3W#n#u@M');
define('SECURE_AUTH_KEY', '@M1YVmtipng1/Xe+[8oZ%[@ux876_9ci3Wt*j-GP19#0RhnQoHMUo6[UKjyzxcn3');
define('LOGGED_IN_KEY', 'qqpD(&AQE)9Y2@x!%&s32-McB2P0jjov:Hqd3b0N3fp7s61326+Hd*057YwtyWvt');
define('NONCE_KEY', ')79[25MGq2p8I*RzlTGBsYH%:40@9RMdg04RC4mixnJc5I2:;SK#_ZRC52&[X;dn');
define('AUTH_SALT', '%39U05*)808-PI%*I5wA;-j;&)j1#(rkB55F!4;3/25k)!Kg*uWHP%%VZ37Kls0#');
define('SECURE_AUTH_SALT', '!Ip#hj8:4hq214I]00)qUTFoU*iABW1ql-)3E(q)q6&F(yxz0-H48VH_FR;)2;4#');
define('LOGGED_IN_SALT', ';OTb71466)M4562ijiGV*f00)qvM-FqP:a6Sr%]e7ddxCpLaLfA#ppq59g4(k*2!');
define('NONCE_SALT', ')74x:jK/O0T+r2&qvS-_h_9lD-kk361)x]*#5eaLmXIs#|e-9kKW#i5~0TJ_9e[3');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '99KLfK8p5_';


define('WP_ALLOW_MULTISITE', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
