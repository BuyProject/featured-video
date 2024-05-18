<?php

// dependencies
require_once(FVP_DIR . 'php/class-html.php');

/**
 * Class containing everything regarding the plugin's settings.
 *
 * @since 1.3
 */
class FVP_Settings
{
	private static $hook = 'options-media.php';

	private static $section = 'fvp-section';

	private static $page = 'media';


	public function __construct()
	{
		FVP_HTML::add_screens(self::$hook);

		add_action('admin_init', array($this, 'settings_init'));
	}


	/**
	 * Initializes the plugin settings section, the settings fields and registers the options field and save function.
	 *
	 * @see https://codex.wordpress.org/Settings_API
	 * @since 1.0
	 */
	public function settings_init()
	{
		// the FeaturedVideo settings section on the media settings page
		add_settings_section(
			self::$section,
			sprintf(
				'<span id="%s">%s</span>',
				self::$section,
				esc_html__('FeaturedVideos', 'featuredvideo')
			),
			array($this, 'section'),
			self::$page
		);

		// settings fields for auto integration of FeaturedVideos - only available
		// in themes with enabled post-thumbnails / featured images
		if (current_theme_supports('post-thumbnails')) {
			add_settings_field(
				'fvp-mode',
				esc_html__('Display mode', 'featuredvideo'),
				array($this, 'mode'),
				self::$page,
				self::$section
			);

			add_settings_field(
				'fvp-conditions',
				esc_html__('Display Conditions', 'featuredvideo'),
				array($this, 'conditions'),
				self::$page,
				self::$section
			);
		}

		// video sizing options
		add_settings_field(
			'fvp-sizing',
			esc_html__('Video Sizing', 'featuredvideo'),
			array($this, 'sizing'),
			self::$page,
			self::$section
		);

		// video align options
		add_settings_field(
			'fvp-align',
			esc_html__('Video Align', 'featuredvideo'),
			array($this, 'alignment'),
			self::$page,
			self::$section
		);

		// video align options
		add_settings_field(
			'fvp-legal_html',
			esc_html__('Legal HTML', 'featuredvideo'),
			array($this, 'legal_html'),
			self::$page,
			self::$section
		);

		// video default url argument options
		add_settings_field(
			'fvp-defaults',
			esc_html__('Default Arguments', 'featuredvideo'),
			array($this, 'arguments'),
			self::$page,
			self::$section
		);

		// donation and support notice
		add_settings_field(
			'fvp-message',
			esc_html__('Support', 'featuredvideo'),
			array($this, 'message'),
			self::$page,
			self::$section
		);

		// registering the call to the fvp settings validation handler
		register_setting('media', 'fvp-settings', array($this, 'save'));
	}


	/**
	 * The settings section content.
	 * Describes the plugin settings, the PHP functions and the shortcode.
	 *
	 * @since 1.0
	 */
	public function section()
	{
		echo FVP_HTML::html(
			'p',
			array('class' => 'fvp-settings-section'),
			sprintf(esc_html__('To display your FeaturedVideos you can either make use of the automatic replacement, use the %s or manually edit your theme\'s source files to make use of the plugins PHP-functions.', 'featuredvideo'), '<code>[featuredvideo]</code>-Shortcode') .
				sprintf(esc_html__('For more information about Shortcode and PHP functions see the %sContextual Help%s.', 'featuredvideo'), '<a href="#contextual-help" class="help-link">', '</a>')
		);

		if (!current_theme_supports('post-thumbnails')) {
			echo FVP_HTML::description(
				FVP_HTML::html(
					'span',
					array('class' => 'bold'),
					esc_html__('The current theme does not support featured images.', 'featuredvideo')
				) .
					sprintf(
						esc_html__(
							'To display FeaturedVideos you need to use the %1$sShortcode%2$s or %1$sPHP functions%2$s.',
							'featuredvideo'
						),
						'<code>',
						'</code>'
					),
				array('fvp_warning')
			);
		}
	}


	/**
	 * Displays the different usage options of the plugin
	 *
	 * @since 1.7
	 */
	public function mode()
	{
		$options = get_option('fvp-settings');

		echo FVP_HTML::radios(
			'fvp-settings[mode]',
			array(
				'replace' => esc_html__('Replace featured image automatically.', 'featuredvideo'),
				'dynamic' => esc_html__('Replace featured image on click.', 'featuredvideo'),
				'overlay' => esc_html__('Open video overlay when featured image is clicked.', 'featuredvideo'),
				'manual'  => esc_html__('Manual: PHP-functions or shortcodes.', 'featuredvideo'),
			),
			!empty($options['mode']) ? $options['mode'] : 'replace'
		);

		echo FVP_HTML::description(
			sprintf(esc_html__("Automatic integration (options 1-3) requires your theme to make use of WordPress' native %sfeatured image%s functionality.", 'featuredvideo'), '<a href="https://codex.wordpress.org/Post_Thumbnails" target="_blank" rel="noopener noreferrer">', '</a>')
		);

		// Always replace on is_singular() ?
		echo FVP_HTML::conditional(
			'<br />' .
				FVP_HTML::checkbox(
					'fvp-settings[single_replace]',
					sprintf(
						esc_html__(
							'Always use replace mode when viewing %ssingle%s posts and pages.',
							'featuredvideo'
						),
						'<a href="https://codex.wordpress.org/Function_Reference/is_singular" target="_blank" rel="noopener noreferrer">',
						'</a>'
					),
					'1',
					!empty($options['single_replace']) && $options['single_replace']
				),
			array(
				'fvp-settings[mode]' => '!manual',
				'hidden' => !empty($options['mode']) && 'manual' === $options['mode']
			)
		);
	}


	/**
	 * Video replace condition option like is_singular or is_home.
	 *
	 * @since  2.0.0
	 */
	public function conditions()
	{
		$options  = get_option('fvp-settings');

		$auto = !empty($options['mode']) && 'manual' !== $options['mode'];
		$or = sprintf(
			'<em>%s</em>',
			strtoupper(esc_html__('or', 'featuredvideo'))
		);

		echo FVP_HTML::conditional(
			FVP_HTML::description(
				esc_html__('View options are not available in manual mode.', 'featuredvideo')
			),
			array(
				'fvp-settings[mode]' => 'manual',
				'hidden' => $auto,
			)
		);

		echo FVP_HTML::conditional(
			FVP_HTML::description(
				esc_html__('Apply display mode...', 'featuredvideo')
			) .
				FVP_HTML::checkboxes(
					'fvp-settings[conditions]',
					array(
						'single' => sprintf(
							esc_html__('when viewing %ssingle%s posts and pages %s', 'featuredvideo'),
							'<a href="https://codex.wordpress.org/Function_Reference/is_singular" target="_blank" rel="noopener noreferrer">',
							'</a>',
							$or
						),
						'home' => sprintf(
							esc_html__('when on the %spost index page%s %s', 'featuredvideo'),
							'<a href="https://codex.wordpress.org/Function_Reference/is_home" target="_blank" rel="noopener noreferrer">',
							'</a>',
							$or
						),
						'main_query' => sprintf(
							esc_html__('when inside the %smain query%s of each page %s', 'featuredvideo'),
							'<a href="https://developer.wordpress.org/reference/functions/is_main_query/" target="_blank" rel="noopener noreferrer">',
							'</a>',
							$or
						),
						'sticky' => sprintf(
							esc_html__('when displaying %ssticky%s posts.', 'featuredvideo'),
							'<a href="https://codex.wordpress.org/Function_Reference/is_sticky" target="_blank" rel="noopener noreferrer">',
							'</a>'
						),
						'!sticky' => sprintf(
							esc_html__('when displaying not %ssticky%s posts.', 'featuredvideo'),
							'<a href="https://codex.wordpress.org/Function_Reference/is_sticky" target="_blank" rel="noopener noreferrer">',
							'</a>'
						)
					),
					!empty($options['conditions']) ? $options['conditions'] : array()
				) .
				FVP_HTML::description(esc_html__(
					'If none of the above options is selected the display mode will be applied whenever possible.',
					'featuredvideo'
				)),
			array(
				'fvp-settings[mode]' => '!manual',
				'hidden' => !$auto,
			)
		);
	}


	/**
	 * Video width and height settings.
	 *
	 * @since 1.3
	 */
	public function sizing()
	{
		$options = get_option('fvp-settings');
		$responsive =
			!empty($options['sizing']['responsive']) &&
			$options['sizing']['responsive'];

		echo FVP_HTML::checkbox(
			'fvp-settings[sizing][responsive]',
			esc_html__('Responsive', 'featuredvideo'),
			'1',
			$responsive
		);

		echo FVP_HTML::conditional(
			FVP_HTML::labeled_input(
				esc_html__('Width in pixels:', 'featuredvideo'),
				'fvp-settings[sizing][width]',
				array(
					'type' => 'number',
					'size' => 4,
					'value' => !empty($options['sizing']['width']) ?
						$options['sizing']['width'] : 640,
				)
			),
			array(
				'fvp-settings[sizing][responsive]' => '!1',
				'hidden' => $responsive,
			)
		);

		echo FVP_HTML::description(
			sprintf(
				esc_html__('The %1$sresponsive%2$s setting does not work when using the %1$soverlay%2$s display mode and might break completly in some themes - in such cases you should use a fixed width instead.', 'featuredvideo'),
				'<code>',
				'</code>'
			),
			array('fvp_warning')
		);
	}


	/**
	 * How should the videos be aligned?
	 * Only interesting when a fixed size is used.
	 *
	 * @since 1.4
	 */
	public function alignment()
	{
		$options = get_option('fvp-settings');

		echo FVP_HTML::radios(
			'fvp-settings[alignment]',
			array(
				'left'   => esc_html__('left', 'featuredvideo'),
				'center' => esc_html__('center', 'featuredvideo'),
				'right'  => esc_html__('right', 'featuredvideo'),
			),
			!empty($options['alignment']) ? $options['alignment'] : 'center'
		);
	}


	/**
	 * Define which HTML is legal in the video input.
	 *
	 * @since 2.3
	 */
	public function legal_html()
	{
		$options = get_option('fvp-settings');

		echo FVP_HTML::description(
			sprintf(
				esc_html__(
					'By default the video input only accepts %soEmbed urls%s. If you want to insert raw embed codes you need to enable the unsecure HTML here first.',
					'featuredvideo'
				),
				'<a href="https://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank" rel="noopener noreferrer">',
				'</a>'
			)
		);

		echo FVP_HTML::checkboxes(
			'fvp-settings[legal_html]',
			array(
				'iframe' => 'iframe',
				'embed' => 'embed',
				'object' => 'object',
			),
			!empty($options['legal_html']) ? $options['legal_html'] : array()
		);
	}


	/**
	 * Default settings for video embeds. Can be altered on a per video basis
	 * using video embed URL arguments.
	 *
	 * @since 1.4
	 */
	public function arguments()
	{
		$options     = get_option('fvp-settings');
		$autoplay    = !empty($options['autoplay']) ? $options['autoplay'] : array();
		$args        = !empty($options['default_args']) ? $options['default_args'] : array();
		$vimeo       = !empty($args['vimeo'])       ? $args['vimeo'] : array();
		$youtube     = !empty($args['youtube'])     ? $args['youtube'] : array();
		$dailymotion = !empty($args['dailymotion']) ? $args['dailymotion'] : array();

		echo FVP_HTML::tabbed(array(
			'general' => array(
				FVP_HTML::description(
					esc_html__(
						'Not all of the following options might be supported by all providers.',
						'featuredvideo'
					)
				),
				FVP_HTML::checkboxes(
					'fvp-settings[default_args][general]',
					array(
						'loop'     => esc_html__('Loop', 'featuredvideo'),
					),
					!empty($args['general']) ? $args['general'] : array()
				),
				FVP_HTML::radios(
					'fvp-settings[autoplay][always]',
					array(
						'1' => esc_html__('Always autoplay.', 'featuredvideo'),
						'0' => esc_html__('Autoplay when...', 'featuredvideo'),
					),
					!empty($autoplay['always']) ? $autoplay['always'] : '0'
				),
				FVP_HTML::checkboxes(
					'fvp-settings[autoplay]',
					array(
						'lazy' => esc_html__('... lazy loading videos.', 'featuredvideo'),
						'single' => sprintf(
							esc_html__('... viewing %ssingle%s posts and pages.', 'featuredvideo'),
							'<a href="https://codex.wordpress.org/Function_Reference/is_singular" target="_blank" rel="noopener noreferrer">',
							'</a>'
						),
					),
					!empty($autoplay) ? $autoplay : array()
				)
			),

			'vimeo' => array(
				FVP_HTML::description(
					esc_html__(
						'If the owner of a video is a Plus member, some of these settings may be overridden by their preferences.',
						'featuredvideo'
					)
				),
				FVP_HTML::colorpicker(
					'Color',
					'fvp-settings[default_args][vimeo][color]',
					!empty($vimeo['color']) ? $vimeo['color'] : null
				),
				FVP_HTML::checkboxes(
					'fvp-settings[default_args][vimeo]',
					array(
						'portrait' => array(
							'value' => '0',
							'label' => esc_html__("Hide user's portrait", 'featuredvideo')
						),
						'title' => array(
							'value' => '0',
							'label' => esc_html__('Hide video title', 'featuredvideo')
						),
						'byline' => array(
							'value' => '0',
							'label' => esc_html__('Hide video byline', 'featuredvideo')
						),
					),
					$vimeo
				),
			),

			'youtube' => array(
				FVP_HTML::checkboxes(
					'fvp-settings[default_args][youtube]',
					array(
						'theme' => array(
							'value' => 'light',
							'label' => esc_html__('Light theme', 'featuredvideo')
						),
						'color' => array(
							'value' => 'white',
							'label' => esc_html__('White highlight color', 'featuredvideo')
						),
						'modestbranding' => esc_html__('Hide YouTube logo', 'featuredvideo'),
						'iv_load_policy' => array(
							'value' => '3',
							'label' => esc_html__('Hide annotations', 'featuredvideo')
						),
						'rel' => array(
							'value' => '0',
							'label' => esc_html__('Hide related videos', 'featuredvideo')
						),
						'fs' => array(
							'value' => '0',
							'label' => esc_html__('Disallow fullscreen', 'featuredvideo')
						),
						'showinfo' => array(
							'value' => '0',
							'label' => esc_html__('Hide video info', 'featuredvideo')
						),
						'enablejsapi' => esc_html__('Enable JavaScript API', 'featuredvideo'),
					),
					$youtube
				),
			),

			'dailymotion' => array(
				FVP_HTML::colorpicker(
					'Foreground color',
					'fvp-settings[default_args][dailymotion][foreground]',
					!empty($dailymotion['foreground']) ? $dailymotion['foreground'] : null
				),
				FVP_HTML::colorpicker(
					'Background color',
					'fvp-settings[default_args][dailymotion][background]',
					!empty($dailymotion['background']) ? $dailymotion['background'] : null
				),
				FVP_HTML::colorpicker(
					'Highlight color',
					'fvp-settings[default_args][dailymotion][highlight]',
					!empty($dailymotion['highlight']) ? $dailymotion['highlight'] : null
				),
				FVP_HTML::labeled_input(
					'Syndication-Key',
					'fvp-settings[default_args][dailymotion][syndication]',
					!empty($dailymotion['syndication']) ? $dailymotion['syndication'] : null
				),
				FVP_HTML::checkboxes(
					'fvp-settings[default_args][dailymotion]',
					array(
						'logo' => array(
							'value' => '0',
							'label' => esc_html__('Hide DailyMotion logo', 'featuredvideo')
						),
						'info' => array(
							'value' => '0',
							'label' => esc_html__('Hide video info', 'featuredvideo')
						),
						'related' => array(
							'value' => '0',
							'label' => esc_html__('Hide related videos', 'featuredvideo')
						),
						'quality' => array(
							'value' => 1080,
							'label' => esc_html__('Turn HD on by default', 'featuredvideo')
						),
					),
					$dailymotion
				),
			),
		));
	}


	/**
	 * Message about support forums, rating and donating.
	 *
	 * @since 1.0
	 */
	public function message()
	{
		echo FVP_HTML::html(
			'p',
			sprintf(
				esc_html__(
					'If you have found a bug or think a specific feature is missing, %slet me know%s in the support forum. Like this plugin? %sRate it%s or %sbuy me a cookie%s!',
					'featuredvideo'
				),
				'<a href="https://wordpress.org/support/plugin/featuredvideo#plugin-title" title="FeaturedVideo Support Forum on WordPress.org" target="_blank" rel="noopener noreferrer" style="font-weight: bold;">',
				'</a>',
				'<a href="https://wordpress.org/support/view/plugin-reviews/featuredvideo#plugin-title" title="Rate FeaturedVideo on WordPress.org" target="_blank" rel="noopener noreferrer" style="font-weight: bold;">',
				'</a>',
				'<a href="https://paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AD8UKMQW2DMM6" title="Gift to the developer!" target="_blank" rel="noopener noreferrer" style="font-weight: bold;">',
				'</a>'
			)
		);
	}


	/**
	 * Function through which all settings are passed before they are saved. Validate the data.
	 *
	 * @since 1.0
	 */
	public function save($input)
	{
		$options = $this->validate($input);
		return $options;
	}


	private function validate($src, $tovalidate = null)
	{
		$patterns = array(
			'number' => '(\d*)',
			'digit'  => '([0-9])',
			'word'   => '([a-z]*)',
			'string' => '(\w*)',
			'hex'    => '#?([0-9a-f]{3}[0-9a-f]{0,3})',
		);

		$datatypes = array(
			'mode' => '(replace|dynamic|overlay|manual)',
			'single_replace' => 'BOOLEAN',
			'conditions' => array(
				'single'     => 'BOOLEAN',
				'home'       => 'BOOLEAN',
				'main_query' => 'BOOLEAN',
				'sticky'     => 'BOOLEAN',
				'!sticky'    => 'BOOLEAN',
			),
			'sizing' => array(
				'responsive' => 'BOOLEAN',
				'width'      => $patterns['number'],
			),
			'alignment' => '(left|center|right)',
			'legal_html' => array(
				'iframe' => 'BOOLEAN',
				'embed' => 'BOOLEAN',
				'object' => 'BOOLEAN',
			),
			'autoplay' => array(
				'always' => 'BOOLEAN',
				'lazy'   => 'BOOLEAN',
				'single' => 'BOOLEAN',
			),
			'default_args' => array(
				'general' => array(
					'loop' => $patterns['digit'],
				),
				'vimeo' => array(
					'portrait' => $patterns['digit'],
					'title'    => $patterns['digit'],
					'byline'   => $patterns['digit'],
					'color'    => $patterns['hex'],
				),
				'youtube' => array(
					'theme'          => $patterns['word'],
					'color'          => $patterns['word'],
					'modestbranding' => $patterns['digit'],
					'iv_load_policy' => $patterns['digit'],
					'fs'             => $patterns['digit'],
					'rel'            => $patterns['digit'],
					'showinfo'       => $patterns['digit'],
					'enablejsapi'    => $patterns['digit'],
				),
				'dailymotion' => array(
					'syndication' => $patterns['number'],
					'logo'        => $patterns['digit'],
					'info'        => $patterns['digit'],
					'related'     => $patterns['digit'],
					'quality'     => $patterns['number'],
					'background'  => $patterns['hex'],
					'foreground'  => $patterns['hex'],
					'highlight'   => $patterns['hex'],
				),
			)
		);

		if (is_null($tovalidate)) {
			$tovalidate = $datatypes;
		}

		$validated = array();

		foreach ($tovalidate as $key => $value) {
			if (!isset($src[$key])) {
				continue;
			}

			if (is_array($value)) {
				$nextleaf = $this->validate($src[$key], $value);
				if (!empty($nextleaf)) {
					$validated[$key] = $nextleaf;
				}
			} elseif ('BOOLEAN' === $value) {
				$validated[$key] = (bool) $src[$key];
			} else {
				preg_match('/' . $value . '/i', $src[$key], $match);
				if (
					!empty($match[1]) ||
					(isset($match[1]) && '0' === $match[1])
				) {
					$validated[$key] = $match[1];
				}
			}
		}

		return $validated;
	}
}
