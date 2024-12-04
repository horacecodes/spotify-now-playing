<?php
/**
 * Plugin activation functionality
 *
 * @package SpotifyNowPlaying
 */

namespace SpotifyNowPlaying\Core;

/**
 * Activator class
 */
class Activator {
    /**
     * Activate the plugin.
     *
     * @return void
     */
    public static function activate() {
        // Add default options.
        add_option('spotify_now_playing_client_id', '');
        add_option('spotify_now_playing_client_secret', '');
        add_option('spotify_now_playing_refresh_token', '');

        // Create necessary directories.
        $upload_dir = wp_upload_dir();
        $plugin_dir = $upload_dir['basedir'] . '/spotify-now-playing';
        
        if (!file_exists($plugin_dir)) {
            wp_mkdir_p($plugin_dir);
        }

        // Add a .htaccess file to protect sensitive data.
        $htaccess_file = $plugin_dir . '/.htaccess';
        if (!file_exists($htaccess_file)) {
            $htaccess_content = "Order deny,allow\nDeny from all";
            file_put_contents($htaccess_file, $htaccess_content);
        }

        // Flush rewrite rules.
        flush_rewrite_rules();
    }
} 