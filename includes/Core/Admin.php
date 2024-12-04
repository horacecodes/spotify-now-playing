<?php
/**
 * Admin functionality
 *
 * @package SpotifyNowPlaying
 */

namespace SpotifyNowPlaying\Core;

/**
 * Admin class
 */
class Admin {
    /**
     * Constructor.
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Add admin menu.
     *
     * @return void
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Spotify Now Playing', 'spotify-now-playing'),
            __('Now Playing', 'spotify-now-playing'),
            'manage_options',
            'spotify-now-playing',
            [$this, 'render_settings_page'],
            'dashicons-controls-play',
            30
        );
    }

    /**
     * Register settings.
     *
     * @return void
     */
    public function register_settings() {
        register_setting('spotify_now_playing_settings', 'spotify_now_playing_client_id');
        register_setting('spotify_now_playing_settings', 'spotify_now_playing_client_secret');
        register_setting('spotify_now_playing_settings', 'spotify_now_playing_refresh_token');

        add_settings_section(
            'spotify_now_playing_settings_section',
            __('API Settings', 'spotify-now-playing'),
            [$this, 'render_settings_section'],
            'spotify-now-playing'
        );

        add_settings_field(
            'spotify_now_playing_client_id',
            __('Client ID', 'spotify-now-playing'),
            [$this, 'render_client_id_field'],
            'spotify-now-playing',
            'spotify_now_playing_settings_section'
        );

        add_settings_field(
            'spotify_now_playing_client_secret',
            __('Client Secret', 'spotify-now-playing'),
            [$this, 'render_client_secret_field'],
            'spotify-now-playing',
            'spotify_now_playing_settings_section'
        );

        add_settings_field(
            'spotify_now_playing_refresh_token',
            __('Refresh Token', 'spotify-now-playing'),
            [$this, 'render_refresh_token_field'],
            'spotify-now-playing',
            'spotify_now_playing_settings_section'
        );
    }

    /**
     * Render settings page.
     *
     * @return void
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap spotify-now-playing-admin">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="notice notice-info">
                <p>
                    <?php esc_html_e('Use the shortcode [spotify_now_playing] to display your currently playing track.', 'spotify-now-playing'); ?>
                </p>
            </div>

            <form action="options.php" method="post">
                <?php
                settings_fields('spotify_now_playing_settings');
                do_settings_sections('spotify-now-playing');
                submit_button();
                ?>
            </form>

            <div class="card">
                <h2><?php esc_html_e('Usage Instructions', 'spotify-now-playing'); ?></h2>
                <p><?php esc_html_e('The widget will automatically update every few seconds to show your currently playing track on Spotify.', 'spotify-now-playing'); ?></p>
                <h3><?php esc_html_e('Shortcode Options', 'spotify-now-playing'); ?></h3>
                <ul>
                    <li><code>show_cover="true|false"</code> - <?php esc_html_e('Show or hide the album cover', 'spotify-now-playing'); ?></li>
                    <li><code>show_artist="true|false"</code> - <?php esc_html_e('Show or hide the artist name', 'spotify-now-playing'); ?></li>
                    <li><code>show_album="true|false"</code> - <?php esc_html_e('Show or hide the album name', 'spotify-now-playing'); ?></li>
                </ul>
                <p>
                    <a href="https://developer.spotify.com/dashboard" target="_blank" class="button button-secondary">
                        <?php esc_html_e('Visit Spotify Developer Dashboard', 'spotify-now-playing'); ?>
                    </a>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Render settings section.
     *
     * @return void
     */
    public function render_settings_section() {
        ?>
        <p>
            <?php esc_html_e('Enter your Spotify API credentials below. These are required for the plugin to access your currently playing track information.', 'spotify-now-playing'); ?>
            <?php esc_html_e('You can obtain these credentials by creating an application in the Spotify Developer Dashboard.', 'spotify-now-playing'); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Important:', 'spotify-now-playing'); ?></strong>
            <?php esc_html_e('Make sure to set the correct redirect URI in your Spotify application settings and keep these credentials secure.', 'spotify-now-playing'); ?>
        </p>
        <?php
    }

    /**
     * Render client ID field.
     *
     * @return void
     */
    public function render_client_id_field() {
        $value = get_option('spotify_now_playing_client_id');
        echo '<input type="text" class="regular-text" name="spotify_now_playing_client_id" value="' . esc_attr($value) . '">';
    }

    /**
     * Render client secret field.
     *
     * @return void
     */
    public function render_client_secret_field() {
        $value = get_option('spotify_now_playing_client_secret');
        echo '<input type="password" class="regular-text" name="spotify_now_playing_client_secret" value="' . esc_attr($value) . '">';
    }

    /**
     * Render refresh token field.
     *
     * @return void
     */
    public function render_refresh_token_field() {
        $value = get_option('spotify_now_playing_refresh_token');
        echo '<input type="password" class="regular-text" name="spotify_now_playing_refresh_token" value="' . esc_attr($value) . '">';
    }
} 