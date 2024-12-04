<?php
/**
 * Plugin deactivation functionality
 *
 * @package SpotifyNowPlaying
 */

namespace SpotifyNowPlaying\Core;

/**
 * Deactivator class
 */
class Deactivator {
    /**
     * Deactivate the plugin.
     *
     * @return void
     */
    public static function deactivate() {
        // Clean up transients.
        delete_transient('spotify_now_playing_access_token');

        // Flush rewrite rules.
        flush_rewrite_rules();
    }
} 