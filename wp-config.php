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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
// Handle HTTPS behind Railway proxy or Vercel proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

// Railway environment detection
if (getenv('RAILWAY_ENVIRONMENT')) {
    // Railway MySQL connection
    define('DB_NAME', getenv('MYSQL_DATABASE'));
    define('DB_USER', getenv('MYSQLUSER'));
    define('DB_PASSWORD', getenv('MYSQLPASSWORD'));
    define('DB_HOST', getenv('MYSQLHOST') . ':' . getenv('MYSQLPORT'));
    define('DB_CHARSET', 'utf8mb4');
    define('DB_COLLATE', '');

    /**
     * Railway Reverse Proxy Configuration
     * This MUST come BEFORE any other WordPress configuration
     */

    // Handle Railway's host proxy - FORCE pdfspark.app
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        $_SERVER['HTTP_HOST'] = 'pdfspark.app';
    } elseif (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false) {
        $_SERVER['HTTP_HOST'] = 'pdfspark.app';
    }

    // Force WordPress to use pdfspark.app ALWAYS
    define('WP_HOME', 'https://pdfspark.app');
    define('WP_SITEURL', 'https://pdfspark.app');

    // Prevent WordPress from trying to "fix" the domain
    define('RELOCATE', false);
    define('WP_ALLOW_REPAIR', false);

    // Trust Railway's proxy headers
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_ADDR'] = trim($forwarded_ips[0]);
    }

    // Stirling PDF API (private Railway internal URL)
    define('STIRLING_API_URL', 'http://stirling-pdf.railway.internal:8080');

    // Production settings
    define('WP_ENVIRONMENT_TYPE', 'production');
    if (!defined('WP_DEBUG')) {
        define('WP_DEBUG', false);
    }
    define('WP_DEBUG_LOG', false);
    define('WP_DEBUG_DISPLAY', false);
} else {
    // Local environment
    /** The name of the database for WordPress */
    define( 'DB_NAME', 'local' );

    /** Database username */
    define( 'DB_USER', 'root' );

    /** Database password */
    define( 'DB_PASSWORD', 'root' );

    /** Database hostname */
    define('DB_HOST', 'localhost:/Users/user/Library/Application Support/Local/run/TxUGaL4mo/mysql/mysqld.sock');

    /** Database charset to use in creating database tables. */
    define( 'DB_CHARSET', 'utf8' );

    /** The database collate type. Don't change this if in doubt. */
    define( 'DB_COLLATE', '' );
}

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'aq&m7hD/PN$kKNe1NxRByGCoT?2{g&]=,j<?Z@[`bW vl*fZO~~L~-YN~KH1t!]X' );
define( 'SECURE_AUTH_KEY',   '}!B,Q @=(MV7TD.jlj)W0Y#. M^[*Ydp_.]P[lbA7FiOfklng j(b_>.N`<?* ~#' );
define( 'LOGGED_IN_KEY',     'i$i+00KsHmpw7O2l:I1HsG2O%T@_2j#ihjjf<2.tb+MdBZhqgfK:-NRbtaV#Dy+d' );
define( 'NONCE_KEY',         'kkI2)0_[9W/KAt:3y^:**:GlI4R(7lyghEwE;JmlpGxRs~@@#pA?qKhepL1#U80[' );
define( 'AUTH_SALT',         'wc/vnV_3h]vn${osx0Fn@8x0,_P!mMLqJ2NXOQX<#zw}fIv#[#5&0Q|8{=>)bKT6' );
define( 'SECURE_AUTH_SALT',  '%LTlmu9^!vH[x4eet.NWzM4QCR) jEBr)K[Q=%NU>gxne^;yl8,CW;@$fqV&h~,q' );
define( 'LOGGED_IN_SALT',    '<>~N7iVfh!d^0Dy@(S7B`Fv[=!E@DWC&8/oEi69|]p+qC04&QLKqsH5nTZ][caew' );
define( 'NONCE_SALT',        'SQ_2&IX#ZPDv83Olx8@nP?>_UJ6<hi@_1~$ovoPKIzk>#3I=^9K+?&6,Y[4{]fI,' );
define( 'WP_CACHE_KEY_SALT', '5zUx/)IW^Qg0X(YJpq:@bggP_YLgeiKwhJTr%{Y<xhQ,bTi<-y &k]X-d~DCEX$_' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
// Debug settings moved to environment-specific section above
if (!getenv('RAILWAY_ENVIRONMENT')) {
    // Local environment debug settings
    if ( ! defined( 'WP_DEBUG' ) ) {
        define( 'WP_DEBUG', true );
    }
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', true );
    define( 'WP_ENVIRONMENT_TYPE', 'local' );
}
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
