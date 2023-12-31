<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * This has been slightly modified (to read environment variables) for use in Docker.
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}


define( 'DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'pos') );

define( 'DB_USER', getenv_docker('WORDPRESS_DB_USER', 'admin') );

define( 'DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', '12345678') );


define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql') );

define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8') );

define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );
define('WP_HOME', 'http://localhost:8080');
define('WP_SITEURL', 'http://localhost:8080');



define('JWT_AUTH_SECRET_KEY', 'wordpressGateWay');
define('JWT_AUTH_CORS_ENABLE', true);
define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         '416c5e816871edc7086ae20f5a4b976dc7062d0a') );
define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '5baf6f5b726abf2adab4195f2e52bc0b0b23730c') );
define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    'c0370ea7f5f6efad3717a5c8806443f5ad2eea18') );
define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        '081d33361a112cd0665508e8259d0ec16a392990') );
define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        '88002d0409afd93e319bb684d7fdbe9060ee8521') );
define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', '34f5bbbf141e4da08d165d434e12df686d65b12d') );
define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   'dd1878a79762da81847fa683792cd356f7bf707f') );
define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       'ac43b91c9d43b5579ca1be5c847874d2471d1775') );
// (See also https://wordpress.stackexchange.com/a/152905/199287)

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );

/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	$_SERVER['HTTPS'] = 'on';
}
// (we include this by default because reverse proxying is extremely common in container environments)

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
	eval($configExtra);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
