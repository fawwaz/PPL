<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'PPL');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '9c<-Iy+JI(uL{ p8i-6-+D-T b%zddmC~{/a8.WTc-UUW<SUCuu3D>Z{LD3_nbiw');
define('SECURE_AUTH_KEY',  '=POoF.[p3e>X`/ELtREUtIl1T{#L){EGI[.;sPP^_coFzQ5Q?FG+W.@Fx7(:@Nb/');
define('LOGGED_IN_KEY',    'h#ZL8G+zW+I S5];Pu?qFI)y+68.GZ?#3j[[?4pk~nYeRfq7uoa@HkxFhr~=+Uv[');
define('NONCE_KEY',        'x}|AI5, Nap>YjZhHcnwYSgG:enx.ak_dShl-XiHjt+^aXA@dvH/~(W_m8 .Td3{');
define('AUTH_SALT',        '#SG(DTFTjsuplJdpj:n~5H<r(I3rc-CHN4H?gvj#1%=J5 tKQQ(b%M-IlthBIa6f');
define('SECURE_AUTH_SALT', 'DCFPAgX0ZlPn)i|eaF$h^{84d4&Uzc-3t|*-z?>C%-0|k&FK8MLgRJ zGJ16$#&n');
define('LOGGED_IN_SALT',   'zF(y0G5Nq5_SnbZu,79-$dRI*s a|s$/,o-JSxfhbuca.Uye$b@OKo3pYl_Zq#R2');
define('NONCE_SALT',       'lwMH7SmvP?]ug-}pXt&ZWQ-qJernz9b@nU,FMCjCYZuG|~>p4&XR**J$ZM;d@@Kg');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
