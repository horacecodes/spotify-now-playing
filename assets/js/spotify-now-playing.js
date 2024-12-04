/**
 * Spotify Now Playing Widget JavaScript
 */

(function($) {
    'use strict';

    // Update interval in milliseconds (3 seconds)
    const UPDATE_INTERVAL = 3000;

    /**
     * Update the widget content
     * @param {Object} data The now playing data
     * @param {HTMLElement} widget The widget element to update
     */
    function updateWidgetContent(data, widget) {
        if (!data.is_playing) {
            widget.html('<div class="spotify-not-playing">Not currently playing</div>');
            return;
        }

        const showCover = widget.data('show-cover') !== 'false';
        const showArtist = widget.data('show-artist') !== 'false';
        const showAlbum = widget.data('show-album') !== 'false';

        let html = `
            <a href="https://open.spotify.com" target="_blank" rel="noopener noreferrer" class="spotify-now-playing__icon">
                <img src="${spotifyNowPlaying.pluginUrl}assets/images/spotify-icon.png" 
                     alt="Spotify" 
                     width="24" 
                     height="24">
            </a>
        `;

        if (showCover && data.album_image_url) {
            html += `
                <div class="spotify-now-playing__cover">
                    <img src="${data.album_image_url}" 
                         alt="${data.album}"
                         width="100"
                         height="100">
                </div>
            `;
        }

        html += `
            <div class="spotify-now-playing__info">
                <div class="spotify-now-playing__title">
                    <a href="${data.song_url}" 
                       target="_blank"
                       rel="noopener noreferrer">
                        ${data.title}
                    </a>
                </div>
        `;

        if (showArtist) {
            html += `
                <div class="spotify-now-playing__artist">
                    ${data.artist}
                </div>
            `;
        }

        if (showAlbum) {
            html += `
                <div class="spotify-now-playing__album">
                    ${data.album}
                </div>
            `;
        }

        html += '</div>';
        widget.html(html);
    }

    /**
     * Check for updates to the now playing state
     */
    function checkNowPlaying() {
        $('.spotify-now-playing').each(function() {
            const widget = $(this);
            const currentSong = widget.data('current-song');

            $.ajax({
                url: spotifyNowPlaying.restUrl + 'spotify-now-playing/v1/now-playing',
                method: 'GET',
                success: function(response) {
                    if (!response.success) {
                        return;
                    }

                    const newSong = response.data;
                    // Only update if the song has changed or playing state has changed
                    if (!currentSong || 
                        currentSong.title !== newSong.title || 
                        currentSong.is_playing !== newSong.is_playing) {
                        
                        updateWidgetContent(newSong, widget);
                        widget.data('current-song', newSong);
                    }
                },
                error: function() {
                    widget.html('<div class="spotify-not-playing">Error fetching now playing data</div>');
                }
            });
        });
    }

    // Initialize the real-time updates
    $(document).ready(function() {
        if ($('.spotify-now-playing').length) {
            // Initial check
            checkNowPlaying();
            // Set up periodic checks
            setInterval(checkNowPlaying, UPDATE_INTERVAL);
        }
    });

})(jQuery); 