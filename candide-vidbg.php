<?php
/*
Plugin Name: Video Background
Plugin URI: http://blakewilson.me/projects/video-background/
Description: jQuery WordPress plugin to easily assign a video background to any element. Awesome.
Author: Blake Wilson
Version: 2.5.0-dev
Author URI: http://blakewilson.me
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
 * Enqueue backend style and script
 */
function vidbg_metabox_scripts() {
	wp_enqueue_style('vidbg-metabox-style', plugins_url('/css/style.css', __FILE__));
  wp_enqueue_script( 'vidbg-admin-backend', plugin_dir_url( __FILE__ ) . '/js/jquery.backend.js' );
}
add_action('admin_enqueue_scripts', 'vidbg_metabox_scripts');



/**
 * Enqueue vidbg jquery script
 */
function vidbg_jquery() {
  wp_enqueue_script('vidbg-video-background', plugins_url('/js/dist/vidbg.min.js', __FILE__), array('jquery'), '1.1', true);
}
add_action('wp_footer', 'vidbg_jquery' );



function vidbg_default_color_palette( $l10n ) {
    $l10n['defaults']['color_picker'] = array(
        'palettes' => array( '#3498db', '#e74c3c', '#374e64', '#2ecc71', '#f1c40f' ),
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
 		'title'         => __( 'Video Background', 'cmb2' ),
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
 		'desc' => __( 'Please specify a link to the fallback image in case the browser does not support Video Background. You can either enter a URL or upload a file.', 'video-background' ),
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
 		'desc' => __( 'Specify the opacity of the overlay. Accepts values between <code>0.0 - 1.0</code>', 'video-background' ),
 		'id'   => $prefix . 'overlay_alpha',
 		'type' => 'own_slider',
		'min'  => '1',
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
 	) );

}
add_action( 'cmb2_admin_init', 'vidbg_register_metabox' );



/**
 * Add inline javascript to footer for video background
 */
function vidbg_initialize_footer() {
  if( is_page() || is_single() || is_home() ) {
    if( is_page() || is_single() ) {
      global $post;
      $container_field = get_post_meta( $post->ID, 'vidbg_metabox_field_container', true );
      $mp4_field = get_post_meta( $post->ID, 'vidbg_metabox_field_mp4', true );
      $webm_field = get_post_meta( $post->ID, 'vidbg_metabox_field_webm', true );
      $poster_field = get_post_meta( $post->ID, 'vidbg_metabox_field_poster', true );
      $overlay = get_post_meta( $post->ID, 'vidbg_metabox_field_overlay', true );
			$overlay_color = get_post_meta( $post->ID, 'vidbg_metabox_field_overlay_color', true );
			$overlay_alpha = get_post_meta( $post->ID, 'vidbg_metabox_field_overlay_alpha', true );
      $no_loop_field = get_post_meta( $post->ID, 'vidbg_metabox_field_no_loop', true );
      $unmute_field = get_post_meta( $post->ID, 'vidbg_metabox_field_unmute', true );
    } elseif ( is_home() && get_option('show_on_front') == 'page' ) {
      $blog_page_id = get_option('page_for_posts');
      $container_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_container', true );
      $mp4_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_mp4', true );
      $webm_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_webm', true );
      $poster_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_poster', true );
      $overlay = get_post_meta( $blog_page_id, 'vidbg_metabox_field_overlay', true );
			$overlay_color = get_post_meta( $blog_page_id, 'vidbg_metabox_field_overlay_color', true );
			$overlay_alpha = get_post_meta( $blog_page_id, 'vidbg_metabox_field_overlay_alpha', true );
      $no_loop_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_no_loop', true );
      $unmute_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_unmute', true );
    } ?>

    <?php if( isset( $container_field ) ): ?>
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
		$overlay_alpha_value = !empty($overlay_alpha) ? $overlay_alpha : '0.3';
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
				var vidbgIsOverlay = <?php echo $overlay; ?>
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
    ?>
    <div class="wrap">
        <h2>Video Background</h2>
        <h3>Usability</h3>
        <p>Video background makes it easy to add responsive, great looking video backgrounds to any element on your website. Below, you will find out what each feild does.</p>
        <ul>
          <li><b>Container</b>: This is probably the most important part of the plugin. This field allows you to specifiy whatever element you want for the video background. Say you wanted a full width/height background video playing on your website, you would simply add <code>body</code> to the field.</li>
          <li><b>MP4</b>: This field represents the link to the .mp4 file. Please place the full link in this field.</li>
          <li><b>WEBM</b>: This field represents the link to the .webm file. Please place the full link in this field.</li>
          <li><b>Poster</b>: The poster is the fallback image in case your browser does not support video background. This fallback image is mostly seen from mobile (video background is not supported on mobile)</li>
          <li><b>Overlay</b>: The overlay feature is useful when your video background is the same color as the text and it makes it hard to see. Using this feature will ensure that your text is visible.</li>
          <li><b>Turn Off Loop</b>: Turning off loop will result in the video playing only once. After the video is fully finished playing, the last frame of the video will be shown.
          <li><b>Play the Audio</b>: Toggling this option will enable the audio on the video you inputted in the mp4/webm fields.
        </ul>
        <h3>Getting Started</h3>
        <p>To implement Video Background on your website, please follow the instructions below.
        <ol>
          <li>Edit the page or post you would like the video background to be on.</li>
          <li>Below the content editor, you should see a metabox titled <b>Video Background</b>. Enter the values for the required fields and publish/update the page.</li>
          <li>Enjoy.</li>
        </ol>
				<p>Alternatively, you can use the shortcode by placing the following code at the bottom of the content editor of the page or post you would like video background on. Here is how it works:<br>
				<code>[vidbg container=&quot;body&quot; mp4=&quot;#&quot; webm=&quot;#&quot; poster=&quot;#&quot; loop=&quot;true&quot; overlay=&quot;false&quot; muted=&quot;false&quot;]</code></p>
				<h3>Questions?</h3>
        <p>If you have any feedback/questions regarding the plugin you can reach me <a href="mailto:hi@blakewilson.me">here.</a>
    </div>
    <?php
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
