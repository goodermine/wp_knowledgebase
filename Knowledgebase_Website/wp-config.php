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
define('DB_NAME', 'notifydo_support');

/** MySQL database username */
define('DB_USER', 'notifydo_support');

/** MySQL database password */
define('DB_PASSWORD', '#V^wZ23_t]w!');

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
define('AUTH_KEY',         '=|C.i_YKbP|tu,1k%-/F_7xEF+k-gr>h}w& /K0><#E<Ws$qRAZrGA&duEnrJnwq');
define('SECURE_AUTH_KEY',  'LqkmXbR^Hu3y5/EN3SEl_;yx7FR&2B`Z?5#-Fm:vBKcE+FH@A$M|Y=:S=qxZAD$>');
define('LOGGED_IN_KEY',    'Qo!:Wf-m_gFoOt2BMmt>ee@F5?yC^7Zqi=:#0~+PhM#,n%TtuGxQI1`(m^01,_{-');
define('NONCE_KEY',        'V*LBo4ADCZU{b%pL>s hFwHK[:P5Gu|Us(YAV6ZT*1-x-f8`~nBKG5<h$zL%1NiX');
define('AUTH_SALT',        'Fv6]s{PZr`U8l*?0{IHERssh!1c7B<i1E.V`Uk4Dn#o{(*u/*Ahvt7c*o`7y_YB3');
define('SECURE_AUTH_SALT', 'w;]UXSAH#}ad[GabX>^/4BB%f$y7M3WRW3d?;i?0&FwYw0A2{Dq1r[s9^N7LEP+:');
define('LOGGED_IN_SALT',   '1rl^Fv)YMa`-bLjMtjX^x)|O)c3~7=GX]@j8`Yi!=PQ;B?);IjoLbzQ1YR|X`W$M');
define('NONCE_SALT',       'RSr4Bu/VLFU$/^B7Sw##O;Zr*Agrk(~WT6,#DlDJx(tYZ<?8a)},YtV8?zdT@>+Q');

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
