<?php
/*
Plugin Name: Video Background
Plugin URI: http://blakewilson.me/projects/video-background/
Description: jQuery WordPress plugin to easily assign a video background to any element. Awesome.
Author: Blake Wilson
Version: 2.5.3
Author URI: http://blakewilson.me
Donate Link: http://paypal.me/blakewilsonme
Text Domain: video-background
Domain Path: /languages
*/



/**
 * Include the metabox framework
 */
if ( file_exists( dirname( __FILE__ ) . '/framework/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/framework/init.php';
}
if ( file_exists( dirname( __FILE__ ) . '/framework/cmb2_field_slider.php' ) ) {
	require_once dirname( __FILE__ ) . '/framework/cmb2_field_slider.php';
}



/**
 * Install the plugin
 * deactivate vidbgpro if installed
 */
function vidbg_install_plugin() {
	if( is_plugin_active( 'video-background-pro/vidbgpro.php') ) {
		deactivate_plugins( 'video-background-pro/vidbgpro.php' );
	}
	delete_option( 'vidbgpro-notice-dismissed' );
}
register_activation_hook( __FILE__, 'vidbg_install_plugin' );



/**
 * Load plugin textdomain.
 */
function vidbg_load_textdomain() {
  load_plugin_textdomain( 'video-background', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'vidbg_load_textdomain' );



/**
 * Enqueue backend style and script
 */
function vidbg_metabox_scripts() {
	wp_enqueue_style('vidbg-metabox-style', plugins_url('/css/vidbg-style.css', __FILE__));
  wp_enqueue_script( 'vidbg-admin-backend', plugin_dir_url( __FILE__ ) . '/js/vidbg-backend.js' );
}
add_action('admin_enqueue_scripts', 'vidbg_metabox_scripts');



/**
 * Enqueue vidbg jquery script
 */
function vidbg_jquery() {
  wp_enqueue_script('vidbg-video-background', plugins_url('/js/dist/vidbg.min.js', __FILE__), array('jquery'), '1.1', true);
}
add_action('wp_footer', 'vidbg_jquery' );



/**
 * Add custom color palette
 */
function vidbg_default_color_palette( $l10n ) {
    $l10n['defaults']['color_picker'] = array(
        'palettes' => array( '#000000', '#3498db', '#e74c3c', '#374e64', '#2ecc71', '#f1c40f' ),
    );
    return $l10n;
}
add_filter( 'cmb2_localized_data', 'vidbg_default_color_palette' );



/**
 * Register metabox and scripts
 */
function vidbg_register_metabox() {
 	$prefix = 'vidbg_metabox_field_';

 	$vidbg_metabox = new_cmb2_box( array(
 		'id'            => 'vidbg-metabox',
 		'title'         => __( 'Video Background', 'video-background' ),
 		'object_types'  => array( 'post', 'page' ),
 		'context'    => 'normal',
 		'priority'   => 'high',
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Container', 'video-background' ),
 		'desc' => __( 'Please specify the container you would like your video background to be in.<br>ex: <code>.header</code> or <code>body</code>', 'video-background' ),
 		'id'   => $prefix . 'container',
 		'type' => 'text',
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Link to .mp4', 'video-background' ),
 		'desc' => __( 'Please specify the link to the .mp4 file. You can either enter a URL or upload a file.<br>For browser compatability, please enter an .mp4 and .webm file for video backgrounds.', 'video-background' ),
 		'id'   => $prefix . 'mp4',
 		'type' => 'file',
 		'options' => array(
 				'add_upload_file_text' => __( 'Upload .mp4 file', 'video-background' ),
 		),
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Link to .webm', 'video-background' ),
 		'desc' => __( 'Please specify the link to the .webm file. You can either enter a URL or upload a file.<br>For browser compatability, please enter an .mp4 and .webm file for video backgrounds.', 'video-background' ),
 		'id'   => $prefix . 'webm',
 		'type' => 'file',
 		'options' => array(
 				'add_upload_file_text' => __( 'Upload .webm file', 'video-background' ),
 		),
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Link to fallback image', 'video-background' ),
 		'desc' => __( 'Please specify a link to the fallback image in case the browser does not support video backgrounds. You can either enter a URL or upload a file.', 'video-background' ),
 		'id'   => $prefix . 'poster',
 		'type' => 'file',
 		'options' => array(
 				'add_upload_file_text' => __( 'Upload fallback image', 'video-background' ),
 		),
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Overlay', 'video-background' ),
 		'desc' => __( 'Add an overlay over the video. This is useful if your text isn\'t readable with a video background.', 'video-background' ),
 		'id'   => $prefix . 'overlay',
 		'type' => 'radio_inline',
		'default' => 'off',
		'options' => array(
				'off' => __( 'Off', 'video-background' ),
				'on' => __( 'On', 'video-background' ),
		),
		'before_row' => '<div id="vidbg_advanced_options">',
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Overlay Color', 'video-background' ),
 		'desc' => __( 'If overlay is enabled, a color will be used for the overlay. You can specify the color here.', 'video-background' ),
 		'id'   => $prefix . 'overlay_color',
 		'type' => 'colorpicker',
 		'default' => '#000',
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Overlay Opacity', 'video-background' ),
 		'desc' => __( 'Specify the opacity of the overlay with the left being mostly transparent and the right being hardly transparent.', 'video-background' ),
 		'id'   => $prefix . 'overlay_alpha',
 		'type' => 'own_slider',
		'min'  => '10',
		'max'  => '99',
		'default' => '30',
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Turn off loop?', 'video-background' ),
 		'desc' => __( 'Turn off the loop for Video Background. Once the video is complete, it will display the last frame of the video.', 'video-background' ),
 		'id'   => $prefix . 'no_loop',
 		'type' => 'radio_inline',
		'default' => 'off',
		'options' => array(
				'off' => __( 'Off', 'video-background' ),
				'on' => __( 'On', 'video-background' ),
		),
 	) );

 	$vidbg_metabox->add_field( array(
 		'name' => __( 'Play the audio?', 'video-background' ),
 		'desc' => __( 'Enabling this will play the audio of the video.', 'video-background' ),
 		'id'   => $prefix . 'unmute',
 		'type' => 'radio_inline',
		'default' => 'off',
		'options' => array(
				'off' => __( 'Off', 'video-background' ),
				'on' => __( 'On', 'video-background' ),
		),
		'after_row' => '</div>',
 	) );

	$vidbg_metabox->add_field( array(
    'before_field' => '<a href="#vidbg_advanced_options" class="button vidbg-button advanced-options-button">Show Advanced options</a>',
    'type' => 'title',
    'id'   => $prefix . 'advanced_button',
  ) );

}
add_action( 'cmb2_admin_init', 'vidbg_register_metabox' );



/**
 * Add inline javascript to footer for video background
 */
function vidbg_initialize_footer() {
  if( is_page() || is_single() || is_home() && get_option( 'show_on_front') == 'page' ) {

		if( is_page() || is_single() ) {
			$the_id = get_the_ID();
		} elseif( is_home() && get_option( 'show_on_front' ) == 'page' ) {
			$the_id = get_option( 'page_for_posts' );
		}

    $container_field = get_post_meta( $the_id, 'vidbg_metabox_field_container', true );
    $mp4_field = get_post_meta( $the_id, 'vidbg_metabox_field_mp4', true );
    $webm_field = get_post_meta( $the_id, 'vidbg_metabox_field_webm', true );
    $poster_field = get_post_meta( $the_id, 'vidbg_metabox_field_poster', true );
    $overlay = get_post_meta( $the_id, 'vidbg_metabox_field_overlay', true );
		$overlay_color = get_post_meta( $the_id, 'vidbg_metabox_field_overlay_color', true );
		$overlay_alpha = get_post_meta( $the_id, 'vidbg_metabox_field_overlay_alpha', true );
    $no_loop_field = get_post_meta( $the_id, 'vidbg_metabox_field_no_loop', true );
    $unmute_field = get_post_meta( $the_id, 'vidbg_metabox_field_unmute', true );

    if( !empty( $container_field ) ): ?>
		<?php
		if( $unmute_field == 'on' ) {
			$boolean_mute = 'false';
		} else {
			$boolean_mute = 'true';
		}

		if( $no_loop_field == 'on' ) {
			$boolean_loop = 'false';
		} else {
			$boolean_loop = 'true';
		}

		if( $overlay == 'on' ) {
			$boolean_overlay = 'true';
		} else {
			$boolean_overlay = 'false';
		}

		$overlay_color_value = !empty($overlay_color) ? $overlay_color : '#000';
		$overlay_alpha_value = !empty($overlay_alpha) ? '0.' . $overlay_alpha : '0.3';
		?>
    <script type="text/javascript">
      jQuery(function($){
				var vidbgContainerValue = '<?php echo $container_field; ?>';
				var vidbgMp4Value = '<?php echo $mp4_field; ?>';
				var vidbgWebmValue = '<?php echo $webm_field; ?>';
				var vidbgPosterValue = '<?php echo $poster_field; ?>';
				var vidbgIsMuted = <?php echo $boolean_mute; ?>;
				var vidbgIsLoop = <?php echo $boolean_loop; ?>;
				var vidbgIsOverlay = <?php echo $boolean_overlay; ?>;
				var vidbgOverlayColor = '<?php echo $overlay_color_value; ?>';
				var vidbgOverlayAlpha = '<?php echo $overlay_alpha_value; ?>';

	      $(vidbgContainerValue).vidbg({
	        'mp4': vidbgMp4Value,
	        'webm': vidbgWebmValue,
	        'poster': vidbgPosterValue,
	      }, {
					muted: vidbgIsMuted,
					loop: vidbgIsLoop,
					overlay: vidbgIsOverlay,
					overlayColor: vidbgOverlayColor,
					overlayAlpha: vidbgOverlayAlpha,
	      });
      });
    </script>
    <?php endif;

  }
}
add_action( 'wp_footer', 'vidbg_initialize_footer' );



/**
 * Shortcode for v1.0.x versions
 */
function candide_video_background( $atts , $content = null ) {
    // Attributes
	extract(
		shortcode_atts(
			array(
				'container' 			=> 'body',
				'mp4' 						=> '#',
				'webm' 						=> '#',
				'poster' 					=> '#',
				'muted' 					=> 'true',
				'loop' 						=> 'true',
				'overlay' 				=> 'false',
				'overlay_color' 	=> '#000',
				'overlay_alpha' 	=> '0.3',
			), $atts , 'vidbg'
		)
	);

    // Put It Together
    ob_start(); ?>
    <script>
      jQuery(function($){
				var vidbgContainerValue = '<?php echo $container; ?>';
				var vidbgMp4Value = '<?php echo $mp4; ?>';
				var vidbgWebmValue = '<?php echo $webm; ?>';
				var vidbgPosterValue = '<?php echo $poster; ?>';
				var vidbgIsMuted = <?php echo $muted; ?>;
				var vidbgIsLoop = <?php echo $loop ?>;
				var vidbgIsOverlay = <?php echo $overlay; ?>;
				var vidbgOverlayColor = '<?php echo $overlay_color; ?>';
				var vidbgOverlayAlpha = '<?php echo $overlay_alpha; ?>';

        $(vidbgContainerValue).vidbg({
          'mp4': vidbgMp4Value,
          'webm': vidbgWebmValue,
          'poster': vidbgPosterValue,
				}, {
					muted: vidbgIsMuted,
					loop: vidbgIsLoop,
					overlay: vidbgIsOverlay,
					overlayColor: vidbgOverlayColor,
					overlayAlpha: vidbgOverlayAlpha,
        });
      });
    <?php

    $outputbefore = ob_get_clean();
    $outputafter = '</script>';

    //Return

    return $outputbefore . do_shortcode($content) . $outputafter;
}
add_shortcode( 'vidbg', 'candide_video_background' );



/**
 * Add getting started page
 */
function vidbg_add_gettingstarted() {
  add_options_page(
      'Video Background',
      'Video Background',
      'manage_options',
      'html5-vidbg',
      'vidbg_gettingstarted_page'
  );
}
add_action( 'admin_menu', 'vidbg_add_gettingstarted' );



/**
 * Getting started page content
 */
function vidbg_gettingstarted_page() {
	echo '<div class="wrap">';
		_e( '<h2>Video Background</h2>', 'video-background' );
		_e( '<p>Video background makes it easy to add responsive, great looking video backgrounds to any element on your website.</p>', 'video-background' );
		_e( '<h3>Getting Started</h3>', 'video-background' );
		_e( '<p>To implement Video Background on your website, please follow the instructions below.', 'video-background' );
		echo '<ol>';
			_e( '<li>Edit the page or post you would like the video background to appear on.</li>', 'video-background' );
			_e( '<li>Below the content editor, you should see a metabox titled <b>Video Background</b>. Enter the values for the required fields and publish/update the page.</li>', 'video-background' );
			_e( '<li>Enjoy.</li>', 'video-background' );
		echo '</ol>';
		_e( '<p>Alternatively, you can use the shortcode by placing the following code at the bottom of the content editor of the page or post you would like the video background to appear on. Here is how it works:</p>', 'video-background' );
		echo '<p><code>[vidbg container=&quot;body&quot; mp4=&quot;#&quot; webm=&quot;#&quot; poster=&quot;#&quot; loop=&quot;true&quot; overlay=&quot;false&quot; overlay_color=&quot;#000&quot; overlay_alpha=&quot;0.3&quot; muted=&quot;false&quot;]</code></p>';
		_e( '<a href="http://blakewilson.me/projects/video-background/" class="button" target="_blank">Further Documentation</a>', 'video-background' );
		_e( '<h3>Questions?</h3>', 'video-background' );
		_e( '<p>If you have any feedback/questions regarding the plugin you can reach me <a href="https://wordpress.org/support/plugin/video-background" target="_blank">here.</a>', 'video-background' );
		_e( '<h3>Support</h3>', 'video-background' );
		_e( '<p>If you like Video Background and want to show your support, consider purchasing the <a href="http://pushlabs.co/video-background-pro" target="_blank">pro version</a>. It comes with plenty of helpful features that make your life easier like:</p>', 'video-background' );
		echo '<ul>';
			_e( '<li>YouTube Integration</li>', 'video-background' );
			_e( '<li>Visual Composer Integration</li>', 'video-background' );
			_e( '<li>Page Builder by SiteOrigin Integration</li>', 'video-background' );
			_e( '<li>Frontend Play/Pause Button Option</li>', 'video-background' );
			_e( '<li>Frontend Volume Button Option</li>', 'video-background' );
			_e( '<li>Overlay Image Textures</li>', 'video-background' );
			_e( '<li>Extensive Documentation</li>', 'video-background' );
			_e( '<li>Video Tutorials</li>', 'video-background' );
			_e( '<li>And Much More!</li>', 'video-background' );
		echo '</ul>';
		_e( '<a href="http://pushlabs.co/video-background-pro" class="button button-primary" target="_blank">Purchase Video Background Pro</a>', 'video-background' );
		_e( ' <a href="https://twitter.com/intent/follow?screen_name=blakewilsonme" class="button button-primary vidbg-twitter" target="_blank">Get Updates on Twitter</a>', 'video-background' );
	echo '</div>';
}



/**
 * Add getting started link on plugin page
 */
function vidbg_gettingstarted_link($links) {
  $gettingstarted_link = '<a href="options-general.php?page=html5-vidbg">Getting Started</a>';
  array_unshift($links, $gettingstarted_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'vidbg_gettingstarted_link' );



/**
 * Create function to see if WordPress version is 4.2 or higher
 */
if ( ! function_exists( 'vidbg_is_wp_version' ) ) {
	function vidbg_is_wp_version( $version = '4.2' ) {
		global $wp_version;

		if ( version_compare( $wp_version, $version, '>=' ) ) {
			return true;
		} else {
			return false;
		}
	}
}



/**
 * If WP version is 4.2 or higher display premium notice message
 */
if( vidbg_is_wp_version() == true ) {
	require_once( plugin_dir_path( __FILE__ )  . '/admin_premium_notice.php' );
}
