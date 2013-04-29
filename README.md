# Featured Video Plus - WordPress Plugin #
Add Featured Videos to your posts and pages. Works like magic with most themes which use Featured Images. Local Media, YouTube, Vimeo, Dailymotion.

[On WordPress.org](http://wordpress.org/extend/plugins/featured-video-plus/)

## Description ##
*A picture is worth a thousand words. How many words is a video worth?*

This plugin enables you to define Featured Videos, which, if set, take the place of Featured Images. There are three ways to get the videos onto your page:

1. If your theme already makes use of  [Featured Images](http://codex.wordpress.org/Post_Thumbnails), these will in most themes __automatically__ be replaced by your Featured Videos if available. Alternatively you can
2. insert the `[featured-video-plus]`-__Shortcode__ in your posts or
3. manually make use of the __PHP functions__ in your theme's source files.

Instead of option 1 the plugin can also request the videos using an AJAX request when the featured image is clicked. This reduces load times and gives you the flexibility to display videos in a lightbox to ensure your theme does not break.

See the theme in action on [yrnxt.com](http://yrnxt.com/wordpress/featured-video-plus/). Also take a look at the [Garvan](http://www.web2feel.com/garvan/) video blogging theme.

Beside your __Local Videos__ (`mp4`, `webM` & `ogg/ogv`) you can use videos from __YouTube__, __Vimeo__ and __Dailymotion__. If you miss a certain video platform: [Leave me a note](http://wordpress.org/support/plugin/featured-video-plus). For YouTube and Dailymotion the plugin also features [time-links](http://support.google.com/youtube/bin/answer.py?hl=en&answer=116618).

The plugin adds customization options to your Media Settings. Beside aesthetic individualizations for each video platform's player you can turn off automatic integration, turn on autoplay, define your Dailymotion Syndication Key and tweak video sizing. By default videos try to dynamically fit their parent containers width. Take a look at *Settings -> Media*.

### Shortcode ###

	[featured-video-plus]
	[featured-video-plus width=300]


### PHP functions ###

	the_post_video( $size )
	has_post_video( $post_id )
	get_the_post_video( $post_id, $size )
	get_the_post_video_url( $post_id )
	get_the_post_video_image_url( $post_id, $fallback )
	get_the_post_video_image( $post_id )

All parameters are optional. If no `$post_id` is given the current post's ID will be used. `$size` is either a string keyword (`thumbnail`, `medium`, `large` or `full`) or a 2-item array representing width and height in pixels, e.g. array(560,320). $fallback by default is false, when set to true this will return the fallback URL for local videos.

## Changelog ##

### 1.7: 2013-04-30 ###
* Added functionality to display featured video in an lightbox using AJAX on featured image click ([*](http://wordpress.org/support/topic/lightbox-video-on-featured-image-click), [*](http://www.web2feel.com/garvan/))
* Added functionality to replace featured image with featured video on demand when image is clicked using AJAX
* `get_the_post_video_url` has a new second parameter (boolean) to get the fallback video's URL ([*](http://wordpress.org/support/topic/fallback-video-url))
* Tested with WordPress 3.6

### 1.6.1: 2013-04-18 ###
* Fixed removing featured image when no featured video is specified ([*](http://wordpress.org/support/topic/featured-image-doesnt-save))

### 1.6: 2013-04-16 ###
* Added `get_the_post_video_url($post_id)` PHP-Function
* Added YouTube `enablejsapi` parameter with `playerapiid` (`fvpid + $post_id`) and iframe id ([*](http://wordpress.org/support/topic/need-filter-for-iframe-and-embed-code-manipulation)
* Added a filter for `get_the_post_video`: `get_the_post_video_filter` ([*](http://wordpress.org/support/topic/need-filter-for-iframe-and-embed-code-manipulation)
* Added option for using the featured image as video thumbnail for local videos
* Fixed local videoJS ([*](http://wordpress.org/support/topic/how-to-style-the-player-play-button-pause-button-etc))
* Fixed auto width and height for the Dailymotion and videoJS players
* Fixed YouTube videos for which the plugin cannot access the YouTube API ([*](http://wordpress.org/support/topic/link-appearing-red-in-featured-video-section))

### 1.5.1: 2013-03-27 ###
* Fixed Featured Video box on new-post.php
* Enhanced Featured Image ajax behavior

### 1.5: 2013-03-22 ###
* __AJAXified__ the Featured Video box - just like Featured Images
* Added options for a) disabling VideoJS JS/CSS, b) enabling VideoJS CDN and c) YouTube `wmode`
* Plugin no longer breaks WP image editor ([*](http://wordpress.org/support/topic/breaks-image-scaling-shows-nan))

### 1.4: 2013-03-15 ###
* __WP 3.5 Media Manager__ seamless integrated
* Time-links now available for YouTube and Dailymotion (append #t###1m2s)
* New `autoplay` setting
* Specify your Dailymotion Syndication Key
* Added `get_the_post_video_image` & `get_the_post_video_image_url`
* Local videos no longer break when domain changes or attachment is edited
* Better Featured Image handling

### 1.3: 2013-01-16 ###
* __Internationalization__: Added German translations
* Added customizations for YouTube and Dailymotion
* Revamped video sizing
* Better error handling
* Contextual help on media settings and post edit screen
* LiveLeak (very experimental, they have no API)

### 1.2: 2013-01-09 ###
* __Local Videos__: mp4, webm, ogg
* More dynamic user interface
* Minimized JS and CSS

### 1.1: 2012-12-16 ###
* __Dailymotion__
* Fixed YouTube time-links
* Enhanced interaction of Featured Videos & Featured Images

### 1.0: 2012-12-13 ###
* Release

