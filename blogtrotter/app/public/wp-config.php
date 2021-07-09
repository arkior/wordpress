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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ti9GkmmJjpVS0sTxE5kXKxnKB2K1nbuIbdbo4fNwhqYdIijl518fy/X4dfU/yxB5LwMW3QMKfYzdPf459qnn5Q==');
define('SECURE_AUTH_KEY',  'R+l6tCqbZX7stz0VOmsavzNHHHQ9XlfAQPs6OFzdyYAQKHvX0PolVHBwv5OCjjybuQGrLqf0tb18/6+e9jefbA==');
define('LOGGED_IN_KEY',    'rVSNANsuwJvnyaTrJLc2w+73/t4ZUS7R3/28c9Ec7Al0Ls6azv9MbTXxmQM+7f+XhGcZfYelCUF9nefWGFR2eg==');
define('NONCE_KEY',        'HPjK1VXjLHzNr4MwLNIUhJER1ykZ+8kCGlCP5eZ9/sRALbP+mdRH11EAIUwevWrC7KCe5wNfWOoCIyTaIma9Bg==');
define('AUTH_SALT',        'aSp7QQd9GimrGaCkx1taMI0xj+BK+x2Tr4/t3/Rkw9203arGZBP2oHLXcLEINJsr/FYhNYz6GoeyVhuYrxm3dg==');
define('SECURE_AUTH_SALT', '1gPkPTIpaL4GRq2iZrLRQx2BfuvdN6K0aD3YMn9+wlAUpOkDXzfC61J/aFPvFm4SRzTro9nBsyclSXW5+o7Y4w==');
define('LOGGED_IN_SALT',   'v2/X7oo8qDaq9j4ZUciCdSf8PJIdx5mQrCR67qG3/bxLc7852NXeaII1VQEY71EqLnSPkaRgxYtRcJDsk7WAwg==');
define('NONCE_SALT',       'mA2jBkApLSnBEhc9j+Qpu/WjS02hMVEnizpYwYjW0jEu5hF2oPgU4GuL9CNzGm8DiGJi7bmepz4+m+w3Ge+60Q==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
