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
define('DB_NAME', 'sheemqnh_swwpdb');

/** MySQL database username */
define('DB_USER', 'sheemqnh_swwpdb');

/** MySQL database password */
define('DB_PASSWORD', '.N-S2r78p8');

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
define('AUTH_KEY',         'gw7duayrqvmjd50w98v7r3g3g6auouxr0yhjjzkuorkibcxiel7ae0qupqiikvyf');
define('SECURE_AUTH_KEY',  'yb9gawhqdow0kwoleil99puxrxwf0n7tayapbtqj4ruu9repccmbqtpmagtfmfsz');
define('LOGGED_IN_KEY',    'scrhtsxgsmtxbgtcmfmv3ajndkcutcbajqy1baq103qgq8w1apgozcyylncknjur');
define('NONCE_KEY',        '9pxhj7seymer8g8rtr850rsx4jsobn3wpwvfhhlqsr6ccyh6prd05hcqt1w81idt');
define('AUTH_SALT',        'n8skcwcgzjrsxvvbqatr9xcyiuh4sf99rsjgjvmoza5nclwuxujrhdmryzmsdhsq');
define('SECURE_AUTH_SALT', 'vjdguzyzfa23bikg7eqmyumtglwf5skumin2ynn6ys92vyyyt760bosro7ivrhyw');
define('LOGGED_IN_SALT',   'nziky1ft1eunl2xatp8nhf66mvgyx4zhwbc5fdrk8nb93pkn3y5thviruq0ert6y');
define('NONCE_SALT',       'pdznhviepy1mxm2fmz7ewia1iyccpcc4fvaf37ndymwft3chrvmkyombkqcs7tme');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpzp_';

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
