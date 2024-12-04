<?php
/**
 * Template for rendering the Spotify Now Playing widget
 *
 * @package SpotifyNowPlaying
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

$spotify_api = new SpotifyNowPlaying\Api\SpotifyApi();
$now_playing = $spotify_api->get_now_playing();

if (!$now_playing || !$now_playing['is_playing']) {
    return '<div class="spotify-now-playing spotify-not-playing">' .
        esc_html__('Not currently playing', 'spotify-now-playing') .
        '</div>';
}

$show_cover = filter_var($atts['show_cover'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
$show_artist = filter_var($atts['show_artist'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
$show_album = filter_var($atts['show_album'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
?>
<div class="spotify-now-playing">
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