# Spotify Now Playing WordPress Plugin

Display your currently playing Spotify track on your WordPress site with a beautiful, responsive widget.

## Features

- Display currently playing track information including title, artist, and album
- Show/hide album artwork
- Auto-refresh functionality
- Responsive design
- Shortcode support with customizable options
- Clean, modern UI that matches Spotify's design language
- Secure API credential storage

## Installation

1. Download the plugin zip file
2. Go to WordPress admin panel > Plugins > Add New
3. Click "Upload Plugin" and select the downloaded zip file
4. Click "Install Now" and then "Activate"

## Configuration

1. Create a Spotify Application:
   - Go to [Spotify Developer Dashboard](https://developer.spotify.com/dashboard)
   - Log in with your Spotify account
   - Click "Create an App"
   - Fill out the name and description
   - Click "Create"
   - Note your Client ID and Client Secret
   - Add `http://your-site.com/wp-admin/options-general.php?page=spotify-now-playing` as a Redirect URI

2. Get your Refresh Token:
   - Go to WordPress admin panel > Settings > Spotify Now Playing
   - Enter your Client ID and Client Secret
   - Click "Authorize with Spotify"
   - Grant the necessary permissions
   - The plugin will automatically save your refresh token

## Usage

### Basic Shortcode
```
[spotify_now_playing]
```

### Shortcode with Options
```
[spotify_now_playing show_cover="true" show_artist="true" show_album="true"]
```

### Available Options
- `show_cover`: Show/hide album artwork (default: true)
- `show_artist`: Show/hide artist name (default: true)
- `show_album`: Show/hide album name (default: true)

## Styling

The plugin comes with a default style that matches Spotify's design language. You can customize the appearance by adding custom CSS to your theme.

## Security

The plugin securely stores your Spotify API credentials in WordPress options and implements all necessary security measures to protect your data.

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Spotify Premium account

## Support

For support, feature requests, or bug reports, please visit the [plugin's GitHub repository](https://github.com/pixamstudio/spotify-now-playing).

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Created by Pixam Studio
Based on the Spotify Web API 