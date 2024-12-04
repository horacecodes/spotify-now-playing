<?php
/**
 * Spotify API wrapper class
 *
 * @package SpotifyNowPlaying
 */

namespace SpotifyNowPlaying\Api;

/**
 * Spotify API wrapper class
 */
class SpotifyApi {
    /**
     * API endpoints
     */
    const TOKEN_ENDPOINT = 'https://accounts.spotify.com/api/token';
    const NOW_PLAYING_ENDPOINT = 'https://api.spotify.com/v1/me/player/currently-playing';

    /**
     * Get the currently playing track.
     *
     * @return array|false Track data or false on failure.
     */
    public function get_now_playing() {
        try {
            $access_token = $this->get_access_token();
            if (!$access_token) {
                return false;
            }

            $response = wp_remote_get(self::NOW_PLAYING_ENDPOINT, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
            ]);

            if (is_wp_error($response)) {
                return false;
            }

            $status = wp_remote_retrieve_response_code($response);
            if ($status === 204 || $status > 400) {
                return false;
            }

            $body = json_decode(wp_remote_retrieve_body($response), true);
            if (!$body) {
                return false;
            }

            return [
                'is_playing' => $body['is_playing'],
                'title' => $body['item']['name'],
                'artist' => implode(', ', array_map(function($artist) {
                    return $artist['name'];
                }, $body['item']['artists'])),
                'album' => $body['item']['album']['name'],
                'album_image_url' => $body['item']['album']['images'][0]['url'],
                'song_url' => $body['item']['external_urls']['spotify'],
            ];
        } catch (\Exception $e) {
            error_log('Spotify Now Playing Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get access token using the refresh token.
     *
     * @return string|false Access token or false on failure.
     */
    private function get_access_token() {
        $client_id = get_option('spotify_now_playing_client_id');
        $client_secret = get_option('spotify_now_playing_client_secret');
        $refresh_token = get_option('spotify_now_playing_refresh_token');

        if (!$client_id || !$client_secret || !$refresh_token) {
            return false;
        }

        $basic = base64_encode($client_id . ':' . $client_secret);

        $response = wp_remote_post(self::TOKEN_ENDPOINT, [
            'headers' => [
                'Authorization' => 'Basic ' . $basic,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
            ],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return $body['access_token'] ?? false;
    }
} 