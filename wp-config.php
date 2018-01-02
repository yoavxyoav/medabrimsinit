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
define('DB_NAME', 'medabrim_chinasitedb1');

/** MySQL database username */
define('DB_USER', 'medabrim_wproot');

/** MySQL database password */
define('DB_PASSWORD', 'TuLaoshiis#1!');

/** MySQL hostname */
define('DB_HOST', 'medabrimsinit.com');


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
define('AUTH_KEY',         'H,F4_eS/J@Y8tA3fv,j5yyOT&lg@jZgxcJEKLHt<V>a0]udR?#3d&s(VDDC^|Z6@');
define('SECURE_AUTH_KEY',  '.k(X6JTa[svNGs#xqCb0%7@j_vPCXC73#Vk)&<oTjXQ:RF{>j6[^E5(zslt.-X|e');
define('LOGGED_IN_KEY',    '7[Bt[Vai+2cB}>mb.^+0XmuE.Hp$^fPe~}7lZ;,M]:;H[5z7rw6AZ);M]/Al1!X)');
define('NONCE_KEY',        'HSWle-z~Y=5TpATn|3||31#b)#6A?e!Ow0& >q@u_xiV+9-/JRuAVs^K(nXBh^D[');
define('AUTH_SALT',        'r&BGf52IFIfF*6Y+*;KlJc(d`d;U7$3(4iecQ !yoDgUea$JM5QCr%Y Xy|.BMSD');
define('SECURE_AUTH_SALT', '3>7Fxk823nNxM@QZ:7<9WmhBYp$NvGLp%%QoX=}_.|CBwteX[1qW6T(w+f>k}D#O');
define('LOGGED_IN_SALT',   'aIFC|+v;d{X1I_#mwj_`?*]l8IqupC)AEOnlw,Zngqg4l~GR: 5< ?He#vyQg]PE');
define('NONCE_SALT',       'Ck^o)$bI%j{6F{h:< T(R-*gDV8ldIU.8]0zBQL6h^#PuHCXq;*fZ>9bpzaL!hD8');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'db1_';

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

/** enables other languages - added by Yoav **/
define('WP_CONTENT_DIR', realpath($_SERVER['DOCUMENT_ROOT'] . '/wp-content'));

/** ftp credentials - added by Yoav 
define('FTP_HOST', 'localhost');
define('FTP_USER', 'daemon');
define('FTP_PASS', 'daemon'); **/

/** downloading Themes directly - added by Yoav **/
define('FS_METHOD', 'direct');

/** might solve the stuck Cron jobs which make RSS AGGREGATOR not to work - added by Yoav **/

define( 'ALTERNATE_WP_CRON', false );
define( 'WP_CRON_LOCK_TIMEOUT', 1 );

/** added after site was broken by Yoav in order to regain access to Dashboard  **/
/**define('WP_HOME','www.medabrimsinit.com');
define('WP_SITEURL','www.medabrimsinit.com');**/