<?php
/**
 * Frontend functionality
 *
 * @package SpotifyNowPlaying
 */

namespace SpotifyNowPlaying\Core;

use SpotifyNowPlaying\Api\SpotifyApi;

/**
 * Frontend class
 */
class Frontend {
    /**
     * Constructor.
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Enqueue assets.
     *
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'spotify-now-playing',
            SPOTIFY_NOW_PLAYING_URL . 'assets/css/spotify-now-playing.css',
            [],
            SPOTIFY_NOW_PLAYING_VERSION
        );

        wp_enqueue_script(
            'spotify-now-playing',
            SPOTIFY_NOW_PLAYING_URL . 'assets/js/spotify-now-playing.js',
            ['jquery'],
            SPOTIFY_NOW_PLAYING_VERSION,
            true
        );

        wp_localize_script('spotify-now-playing', 'spotifyNowPlaying', [
            'restUrl' => rest_url(),
            'pluginUrl' => SPOTIFY_NOW_PLAYING_URL,
            'nonce' => wp_create_nonce('wp_rest'),
            'i18n' => [
                'notPlaying' => __('Not currently playing', 'spotify-now-playing'),
                'error' => __('Error fetching now playing data', 'spotify-now-playing'),
            ],
        ]);
    }

    /**
     * Render the now playing widget.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public static function render($atts) {
        $spotify_api = new SpotifyApi();
        $now_playing = $spotify_api->get_now_playing();

        if (!$now_playing || !$now_playing['is_playing']) {
            return '<div class="spotify-now-playing spotify-not-playing">' .
                esc_html__('Not currently playing', 'spotify-now-playing') .
                '</div>';
        }

        $show_cover = filter_var($atts['show_cover'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
        $show_artist = filter_var($atts['show_artist'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
        $show_album = filter_var($atts['show_album'] ?? 'true', FILTER_VALIDATE_BOOLEAN);

        ob_start();
        ?>
        <div class="spotify-now-playing"
             data-show-cover="<?php echo esc_attr($show_cover ? 'true' : 'false'); ?>"
             data-show-artist="<?php echo esc_attr($show_artist ? 'true' : 'false'); ?>"
             data-show-album="<?php echo esc_attr($show_album ? 'true' : 'false'); ?>">
            <a href="https://open.spotify.com" target="_blank" rel="noopener noreferrer" class="spotify-now-playing__icon">
                <img src="<?php echo esc_url(SPOTIFY_NOW_PLAYING_URL . 'assets/images/spotify-icon.png'); ?>" 
                     alt="Spotify" 
                     width="24" 
                     height="24">
            </a>
            <?php if ($show_cover && !empty($now_playing['album_image_url'])) : ?>
                <div class="spotify-now-playing__cover">
                    <img src="<?php echo esc_url($now_playing['album_image_url']); ?>" 
                         alt="<?php echo esc_attr($now_playing['album']); ?>"
                         width="100"
                         height="100">
                </div>
            <?php endif; ?>

            <div class="spotify-now-playing__info">
                <div class="spotify-now-playing__title">
                    <a href="<?php echo esc_url($now_playing['song_url']); ?>" 
                       target="_blank"
                       rel="noopener noreferrer">
                        <?php echo esc_html($now_playing['title']); ?>
                    </a>
                </div>

                <?php if ($show_artist) : ?>
                    <div class="spotify-now-playing__artist">
                        <?php echo esc_html($now_playing['artist']); ?>
                    </div>
                <?php endif; ?>

                <?php if ($show_album) : ?>
                    <div class="spotify-now-playing__album">
                        <?php echo esc_html($now_playing['album']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
} 