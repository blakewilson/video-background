<?php
/*
Plugin Name: Video Background
Plugin URI: http://blakewilson.me/projects/video-background/
Description: jQuery WordPress plugin to easily assign a video background to any element. Awesome.
Author: Blake Wilson
Version: 2.5.0
Author URI: http://blakewilson.me
*/

/**
 * Include the metabox framework
 */
if ( file_exists( dirname( __FILE__ ) . '/framework/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/framework/init.php';
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
  wp_enqueue_script('vidbg-video-background', plugins_url('/js/dist/vidbg.min.js', __FILE__), array('jquery'), '1.1.0', true);
}
add_action('wp_footer', 'vidbg_jquery' );


/**
 * @TODO add metabox script
 */





/**
 * Add inline javascript to footer for video background
 */
function vidbg_initialize_footer() {
  if(is_page() || is_single() || is_home()) {
    if(is_page() || is_single()) {
      global $post;
      $container_field = get_post_meta( $post->ID, 'vidbg_metabox_field_container', true );
      $mp4_field = get_post_meta( $post->ID, 'vidbg_metabox_field_mp4', true );
      $webm_field = get_post_meta( $post->ID, 'vidbg_metabox_field_webm', true );
      $poster_field = get_post_meta( $post->ID, 'vidbg_metabox_field_poster', true );
      $pattern_overlay = get_post_meta( $post->ID, 'vidbg_metabox_field_overlay', true );
      $no_loop_field = get_post_meta( $post->ID, 'vidbg_metabox_field_no_loop', true );
      $unmute_field = get_post_meta( $post->ID, 'vidbg_metabox_field_unmute', true );
    } elseif ( is_home() && get_option('show_on_front') == 'page' ) {
      $blog_page_id = get_option('page_for_posts');
      $container_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_container', true );
      $mp4_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_mp4', true );
      $webm_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_webm', true );
      $poster_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_poster', true );
      $pattern_overlay = get_post_meta( $blog_page_id, 'vidbg_metabox_field_overlay', true );
      $no_loop_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_no_loop', true );
      $unmute_field = get_post_meta( $blog_page_id, 'vidbg_metabox_field_unmute', true );
    } ?>
    <?php if( isset( $container_field ) ): ?>
      <script type="text/javascript">
        jQuery(function($){
              $('<?php echo $container_field; ?>').vidbg({
                  'mp4': '<?php echo $mp4_field; ?>',
                  'webm': '<?php echo $webm_field; ?>',
                  'poster': '<?php echo $poster_field; ?>',
              }, {
                // Options
                muted: <?php if($unmute_field == 'on'): ?>false<?php else: ?>true<?php endif; ?>,
                loop: <?php if($no_loop_field == 'on'): ?>false<?php else: ?>true<?php endif; ?>,
								overlay: <?php if($pattern_overlay == 'on'): ?>true<?php else: ?>false<?php endif; ?>,
              });
          });
      </script>
    <?php endif; ?>
  <?php }
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
				'container' => 'body',
				'mp4' 			=> '#',
				'webm' 			=> '#',
				'poster' 		=> '#',
				'muted' 		=> 'true',
				'loop' 			=> 'true',
				'overlay' 	=> 'false',
			), $atts , 'vidbg'
		)
	);

    // Put It Together
    ob_start();
    ?>
    <script>
      jQuery(function($){
        $('<?php echo $container; ?>').vidbg({
          'mp4': '<?php echo $mp4; ?>',
          'webm': '<?php echo $webm; ?>',
          'poster': '<?php echo $poster; ?>',
				}, {
					muted: <?php echo $muted; ?>,
					loop: <?php echo $loop; ?>,
					overlay: <?php echo $overlay; ?>,
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
