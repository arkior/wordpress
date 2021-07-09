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
define('AUTH_KEY',         'wHVVO5TL2o+EzAQk19yNudCzC8E9oAbqXRJwd49Ka32BBFc60ch5POYZIqDMy693f7I9O6IBXbVPiA76mcPEfA==');
define('SECURE_AUTH_KEY',  'AwsAJAGvtioKNAM6FA2ayFgtOaFmY6AA1C/Z1WJvRpE0sC+IkiUu1MgP63Deh0V3VUvJo6ljfibtxEcvxfF1tw==');
define('LOGGED_IN_KEY',    'xSQ+TSlMf85Z/mnkoOa0wad7ZA9Ge6iw0srpBwAhGuLIdpuvHhT65mMJ08DCxFyAXRWNnKvpeTzRsIbReyca7w==');
define('NONCE_KEY',        'cDp5bowcRDWbthW5uMeBjtAL5Lp7ytEZJwuOK4ea09u4hdQpP5WQUfsh5GArDtOyjHCdPnGRFV/5S5zDunW0sw==');
define('AUTH_SALT',        'ky/pRi3pmHMO8m9PMgs4pUe+1kUQqROvDVi38PTo39OkhHSb/PX1lY8JVlEo3sl8LQ+x+xfk0KWuMsyqtMrFqg==');
define('SECURE_AUTH_SALT', 'NyTdeMrHa2OgekiLG8bigRnZrtb1TOZzojAR+vGnCzgz91+KSWiDDqDLmljD4XTUnJfwuPO7i7NvKajbJouBkQ==');
define('LOGGED_IN_SALT',   'E5wAlEsqtwYBD9N14e+CeJ/BlIk750g4aeOZLqmvdfEMoqpKdUez4dUsUV25UQyQ3BTTo3LvwngkSPlzTJKTnQ==');
define('NONCE_SALT',       'yz16RZJf9J1LiauA3Lsg2jBdAh8sjQNe7OvfTxeAg9xGMeWZgw4FavhxOi0z2u3UpuStFWYRi7zaEuOIbzy9+Q==');

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
