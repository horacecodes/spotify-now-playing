<?php
/**
 * Plugin Name: Spotify Now Playing
 * Plugin URI: https://github.com/pixamstudio/spotify-now-playing
 * Description: Display your currently playing Spotify track on your WordPress site with a beautiful, responsive widget.
 * Version: 1.0.0
 * Author: pixamstudio
 * Author URI: https://pixamstudio.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: spotify-now-playing
 * Domain Path: /languages
 *
 * @package SpotifyNowPlaying
 * @author pixamstudio
 * @link https://pixamstudio.com
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Plugin version.
define('SPOTIFY_NOW_PLAYING_VERSION', '1.0.0');
define('SPOTIFY_NOW_PLAYING_PATH', plugin_dir_path(__FILE__));
define('SPOTIFY_NOW_PLAYING_URL', plugin_dir_url(__FILE__));

// Autoload classes.
spl_autoload_register(function ($class) {
    $prefix = 'SpotifyNowPlaying\\';
    $base_dir = SPOTIFY_NOW_PLAYING_PATH . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin.
add_action('plugins_loaded', function () {
    $plugin = new SpotifyNowPlaying\Core\Plugin();
    $plugin->init();
});

// Register activation hook.
register_activation_hook(__FILE__, function () {
    require_once SPOTIFY_NOW_PLAYING_PATH . 'includes/Core/Activator.php';
    SpotifyNowPlaying\Core\Activator::activate();
});

// Register deactivation hook.
register_deactivation_hook(__FILE__, function () {
    require_once SPOTIFY_NOW_PLAYING_PATH . 'includes/Core/Deactivator.php';
    SpotifyNowPlaying\Core\Deactivator::deactivate();
}); 