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

define(‘WP_ALLOW_REPAIR’, true);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'dries_vg');

/** MySQL database username */
define('DB_USER', 'dries_vg');

/** MySQL database password */
define('DB_PASSWORD', 'dries739');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '[md|c-TTXGsi/J89hq-qFg|0KI[,,u5F-+#hski#*8tpPE:LL,Uz):0?9[:lM1MV');
define('SECURE_AUTH_KEY',  '{FB&%SGt4q+>]xO[.hHb,xXbJW|91OudW[j0i.Sr N7miB/*8U%5Yd]-u958ImQ+');
define('LOGGED_IN_KEY',    'Q%J 0Dps<|f#(>Jz.4nE{kOTR,N-,wL0!c}f-WcrhyT=n$gKMWk|1-SPdM=-yF-4');
define('NONCE_KEY',        't*~{XaeO-)N-{CZ~V{uSg{*&t-oD B&zq<il+@#g]yXaMGu2hU#YK|=Rl+)Y5}re');
define('AUTH_SALT',        'qi=_)7:^+YyP[b[k1,y#lm.sMcsf|$`m^-n0G=]p@QV s(STjK3pW@M+d*!7`9NF');
define('SECURE_AUTH_SALT', 'voQ9Z5R<6+$NI{x$h*@~`&6&z/h^m>zI-Q~QKY<PV5xBL{-HjQ/o%li5#<z#G6v9');
define('LOGGED_IN_SALT',   'J>nYC__o^f{sf$K2<v!q9pIy|=V<BZlj,5s=7}Q:8,,ESzC6F |Nwfkvs*Efz_:;');
define('NONCE_SALT',       ';+%QlS7]/Cu*v@eNn()ie b^vtEk|b}p);cW[.;s}U->w>0-cAI(ZQDXWU|j96U,');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
