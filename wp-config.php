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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'maanjaawp' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'DdpL*4JRowN6U?.P-lZSx4^kHr!g~=(R/o$t 8Y(`tJDM5X0ER<+lkTd=nnP Usr' );
define( 'SECURE_AUTH_KEY',  ' DMXpL9E7TSve$P5byv xk|_Q`D58NDRbL$go4@Z|eA{gLh=rsk*P$_MFJl2Ncwk' );
define( 'LOGGED_IN_KEY',    '{wc]nDzkyXcE8$l>58.K)<Jmn/oFGBFcm% ?-&UB M_AE;|[,~Mq,rhb8H^yO7[z' );
define( 'NONCE_KEY',        'zV-_BYmxpM^]K=SNSg)uIC^0)H$(|6FExU=aVCT5mpw^|+5Lbcn_Tw~b+[foxY`9' );
define( 'AUTH_SALT',        '[6Q4^PQu+8|a0se|5E|0?2[0+&M^tTxY56Mg~;LO#M]b<xg[e/a~d%%K`GIOke|w' );
define( 'SECURE_AUTH_SALT', '>HI7:ZvvQ}9(jS/bB-6; 5zwN/kG7Wz,ClHtk)p}dn/!!=4um8^~r~+&=0D2c)j?' );
define( 'LOGGED_IN_SALT',   'pJ]gUJUc~>DnB;L:yh3(-b !ZR5S+Lg,AeG18l,O=w~_b_U:L-RNQaQ][8-!zyrH' );
define( 'NONCE_SALT',       ':pWdi6<${i vI#/.nX/rq`)u(Zt}d-#)0|iZb}a[I]%x$:RAQ+v(wn6J]lOM&Es8' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
