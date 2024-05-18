=== FeaturedVideo ===
Contributors: buyproject
Plugin Name: FeaturedVideo
Plugin URI: https://buycloud.id/wordpress/featuredvideo/
Tags: featured, post, video, videos, image, thumbnail, html5, flash, lazy, overlay, youtube, vimeo, dailymotion, soundcloud, spotify
Author: Ari Sigit
Author URI: https://buycloud.id/
License: GPL-2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.7
Tested up to: 6.5
Stable tag: 2.3.4

Add FeaturedVideos to your posts and pages. Works like magic with most themes which use Featured Images. Local Media, YouTube, Vimeo and many more.



== Description ==
> A picture is worth a thousand words. How many words is a video worth?

FeaturedVideos work like Featured Images, just smoother: Paste a video URL into the designated new box on the post edit screen and the video will be displayed in place of a post image.

There are three ways to get the videos onto your page:

1. **Automagically!** If your theme makes use of WordPress' native [featured image functionality](http://codex.wordpress.org/Post_Thumbnails) you are set: Automatic insertion, lazy loading or lightbox overlays, its your choice. If this does not work you can either
2. insert the `[featuredvideo]`-__Shortcode__ in your posts or
3. manually make use of the __PHP-functions__ in your theme's source files.

For more details, check the [installation](http://wordpress.org/plugins/featuredvideo/installation/) page.

> <strong>Theme compatibility</strong><br>
> Sadly many themes do not follow the WordPress standards and implement their own fancy functions for displaying featured images - those very likely break this plugin. Check out the [FAQ](https://wordpress.org/plugins/featuredvideo/faq/). Another common problem are sliders: Videos, in general, do not like sliders at all.

See the plugin in action on [buycloud.id](https://buycloud.id/wordpress/featuredvideo/). There is a button in the sidebar to switch between the different FeaturedVideo display modes: [Automatic](https://buycloud.id/wordpress/featuredvideo/?setfvpmode=replace), [lazy](https://buycloud.id/wordpress/featuredvideo/?setfvpmode=dynamic) and [overlay](https://buycloud.id/wordpress/featuredvideo/?setfvpmode=overlay).

Besides **Local Videos** you can use videos from a whole lot of external providers like **YouTube**, **Vimeo** and **Dailymotion**. **SoundCloud** and **Spotify** (including playlists) are supported as well. Check the [WordPress Codex](http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F) for a complete list. If some provider is not listed you can always just use an embed code or whatever HTML you like.

After installing the plugin check your site's *Media Settings* (`Settings -> Media` in the administration interface): The plugin adds quite some little helper options there. Change to lazy or overlay mode, tweak video sizing, individualize the look of the most prominent providers' video players and turn on autoplay or video looping. By default videos try to dynamically fit their parent containers width and adjust their size responsively.

> <strong>Support</strong><br>
> I do read all support questions in the [forums](http://wordpress.org/support/plugin/featuredvideo) but cannot reply to all of them. The plugin is an unpaid side project and full support would require more time than I can invest for free for over 20k active installs. If you really need help, consider [buying me a cookie](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AD8UKMQW2DMM6) - best way to attract my attention and to support future enhancements.



== Installation ==

= Installation =

1. Visit your WordPress Administration interface and go to `Plugins -> Add New`
2. Search for `FeaturedVideo`, and click `Install Now` below the plugin's name
3. When the installation finished, click `Activate Plugin`

The plugin is ready to go. Now edit your posts and add video links to the `FeaturedVideo` box on the right! Plugin specific settings can be found under `Settings -> Media`.

= Theme integration =

If the automatic integration fails you can always fallback to either using the shortcode or adjusting your themes sourcecode manually:

**Shortcode**

	[featuredvideo]
	[featuredvideo width=300]

**PHP-functions**

	the_post_video( $size )
	has_post_video( $post_id )
	get_the_post_video( $post_id, $size )
	get_the_post_video_url( $post_id )
	get_the_post_video_image( $post_id )
	get_the_post_video_image_url( $post_id )

All parameters are optional. If no `$post_id` is given the current post's ID will be used. `$size` is either a string keyword (`thumbnail`, `medium`, `large` or `full`) or a 2-item array representing width and height in pixels, e.g. `array(560,320)`.

When editing your theme's sourcecode keep in mind that a future update through WordPress.org might overwrite your changes. Consider creating a child theme to prevent that.



== Screenshots ==

1. A FeaturedVideo in the Twenty Fifteen theme on [buycloud.id](https://buycloud.id/wordpress/featuredvideo).
2. FeaturedVideo and Featured Image boxes on the post edit screen.
3. FeaturedVideo settings on the `Settings -> Media` administration screen.



== Frequently Asked Questions ==

= Why do I just get text back after adding an URL to the FeaturedVideo input? =
If the plugin just returns text instead of the actual video the pasted url is probably not valid or not from a valid video provider. Try inserting the raw embed code instead or [check the docs](http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F) to see which providers are supported.

= How do I use my local videos? =
Click the small media icon in the FeaturedVideo input box on the post edit screen and upload your video or choose it from the media library. WordPress does not support all formats tho, [check this table](http://www.mediaelementjs.com/#devices) for details.

= Why do I not see a FeaturedVideo or image on the frontend at all? =
For the videos to be automatically displayed you need to define a Featured Image. Depending on your FeaturedVideo settings this image will never be shown if a video is set. If your theme does not support featured images the plugin also has no chance of working out of the box.

= Why does the frontend still display the featured image although I added a FeaturedVideo to the post? =
Sadly not all themes work out of the box. Themes need to make use of WordPress' native [Post Thumbnail](http://codex.wordpress.org/Post_Thumbnails) functionality (specifically `get_the_post_thumbnail()` and/or `the_post_thumbnail()`) - these functions are where the plugin can hook into the theme and modify what is displayed. Consider contacting the theme's creator or modifying the theme's sourcecode in order to add the plugin's [PHP-functions](https://wordpress.org/plugins/featuredvideo/installation/).

= How can I make the videos fit into their designated space in my theme? =
Take a look at your media settings and try using a fixed width instead of responsive sizing.

= How can I make the plugin work with infinite scrolling? =
While the plugin tries to handle infinite scrolling automatically, it does not work for all configurations. In those cases you will want to manually call `initFeaturedVideoPlus()` using JavaScript everytime new articles have been loaded. Most infinite scroll plugins should have some kind of post-load hook.

= Can I help translating the plugin? =
Yes, please! Check out the official [FeaturedVideo Translation Project](https://translate.wordpress.org/projects/wp-plugins/featuredvideo).



== Changelog ==

## 2.3.4: 2024-05-18 ##
* Release
