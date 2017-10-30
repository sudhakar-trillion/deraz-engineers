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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
if($_SERVER['HTTP_HOST'] == 'localhost')
{
define('DB_NAME', 'aravinteractive');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

}
else
{
define('DB_NAME', 'vsksamsu_aravdb');

/** MySQL database username */
define('DB_USER', 'vsksamsu_arav');

/** MySQL database password */
define('DB_PASSWORD', 'CC7Dyx@nH$b#');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

}
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '<4`S ]`KJSmEp93t7PufKasfl>SCZ~LVtx7qZKm*L3>,L~%7&xyRXKO/1T?.$)w]');
define('SECURE_AUTH_KEY',  'zE?_RTI=Q;g>12?vHotyj_9!|3YNxG.|dXCI7m$J^{YDhX(L-|pCVq+cGd+NwOVf');
define('LOGGED_IN_KEY',    'J0)ER-t-T-~PE~yQ&w<gj$2ln~(UG2m8^m~:sjv?h,xJy0<%?J2bc#ZQwAD}u0 ,');
define('NONCE_KEY',        'cFqxg;8c8(pCydHL<0(jv=6}[wu@X1 nVq.D_(Wo?RbE(|26/P0n?w^<!q_2Y:-e');
define('AUTH_SALT',        'NX&j<5w~Bl%#/H!.j*&4,r3`de~Y5h ~lL<#X ~2F5T>R>a.X0 !m$D/j^e#6Z,$');
define('SECURE_AUTH_SALT', '<#I`Fr!xx3Rf3W|o+N-m.2ihbv&W2OJ:RKRP#yF:cMw8C+|>Nt)*Bte69T}!w4 h');
define('LOGGED_IN_SALT',   'U!@Zt0&uiuVyHuL&Vl/p_M2{S}E*SCQt}7,j#MJa8a$mGj%3.T5B$G/=El_q`-2=');
define('NONCE_SALT',       'QNp}TxL==lVcS W,V7xKrL0N[=z o:dsSH!~pgH0z1<iACBANVo8tzU^@~L0xps;');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'arav';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
