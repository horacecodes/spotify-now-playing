<?php
/**
 * Main plugin class
 *
 * @package SpotifyNowPlaying
 */

namespace SpotifyNowPlaying\Core;

/**
 * Main plugin class
 */
class Plugin {
    /**
     * Plugin instance.
     *
     * @var Plugin|null
     */
    private static $instance = null;

    /**
     * Initialize the plugin.
     *
     * @return void
     */
    public function init() {
        // Load plugin dependencies.
        $this->load_dependencies();

        // Initialize admin.
        if (is_admin()) {
            new Admin();
        }

        // Initialize frontend.
        new Frontend();

        // Initialize REST API.
        new RestApi();

        // Add shortcode.
        add_shortcode('spotify_now_playing', [$this, 'render_shortcode']);
    }

    /**
     * Load plugin dependencies.
     *
     * @return void
     */
    private function load_dependencies() {
        require_once SPOTIFY_NOW_PLAYING_PATH . 'includes/Core/Admin.php';
        require_once SPOTIFY_NOW_PLAYING_PATH . 'includes/Core/Frontend.php';
        require_once SPOTIFY_NOW_PLAYING_PATH . 'includes/Core/RestApi.php';
        require_once SPOTIFY_NOW_PLAYING_PATH . 'includes/Api/SpotifyApi.php';
    }

    /**
     * Render the shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts([
            'show_cover' => 'true',
            'show_artist' => 'true',
            'show_album' => 'true',
        ], $atts);

        return Frontend::render($atts);
    }

    /**
     * Get plugin instance.
     *
     * @return Plugin
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
} 