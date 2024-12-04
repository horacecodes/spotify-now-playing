<?php
/**
 * REST API functionality
 *
 * @package SpotifyNowPlaying
 */

namespace SpotifyNowPlaying\Core;

use SpotifyNowPlaying\Api\SpotifyApi;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;

/**
 * REST API class
 */
class RestApi {
    /**
     * Constructor.
     */
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register REST API routes.
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route('spotify-now-playing/v1', '/now-playing', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_now_playing'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Get now playing data.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response
     */
    public function get_now_playing(WP_REST_Request $request) {
        $spotify_api = new SpotifyApi();
        $now_playing = $spotify_api->get_now_playing();

        if (!$now_playing) {
            return new WP_REST_Response([
                'success' => false,
                'message' => __('Failed to fetch now playing data', 'spotify-now-playing'),
            ], 500);
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => $now_playing,
        ]);
    }
} 