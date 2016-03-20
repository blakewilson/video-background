<?php
/*
Plugin Name: Video Background
Plugin URI: http://blakewilson.me/projects/video-background/
Description: jQuery WordPress plugin to easily assign a video background to any element. Awesome.
Author: Blake Wilson
Version: 2.4.1
Author URI: http://blakewilson.me
*/

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
  wp_enqueue_script('vidbg-video-background', plugins_url('/js/dist/vidbg.min.js', __FILE__), array('jquery'), '1.0.0', true);
}
add_action('wp_footer', 'vidbg_jquery' );

/**
 * Add Metabox for video background v2
 * Added page post type
 */
function vidbg_metabox_add( $post_type ) {
  $post_types = array( 'post', 'page' );
  if ( in_array( $post_type, $post_types )) {
    add_meta_box( 'vidbg-metabox', 'Video Background', 'vidbg_meta_box_cb', $post_type, 'normal', 'high' );
  }
}
add_action( 'add_meta_boxes', 'vidbg_metabox_add' );

/**
 * Create values and metabox form
 */
function vidbg_meta_box_cb( $post ) {
  $values = get_post_custom( $post->ID );
  $container = isset( $values['vidbg_metabox_field_container'] ) ? esc_attr( $values['vidbg_metabox_field_container'][0] ) : '';
  $mp4 = isset( $values['vidbg_metabox_field_mp4'] ) ? esc_attr( $values['vidbg_metabox_field_mp4'][0] ) : '';
  $webm = isset( $values['vidbg_metabox_field_webm'] ) ? esc_attr( $values['vidbg_metabox_field_webm'][0] ) : '';
  $poster = isset( $values['vidbg_metabox_field_poster'] ) ? esc_attr( $values['vidbg_metabox_field_poster'][0] ) : '';
  $overlay = isset( $values['vidbg_metabox_field_overlay'] ) ? esc_attr( $values['vidbg_metabox_field_overlay'][0] ) : '';
  $noloop = isset( $values['vidbg_metabox_field_no_loop'] ) ? esc_attr( $values['vidbg_metabox_field_no_loop'][0] ) : '';
  $unmute = isset( $values['vidbg_metabox_field_unmute'] ) ? esc_attr( $values['vidbg_metabox_field_unmute'][0] ) : '';
  wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
  ?>
  <table class="form-table vidbg_metabox">
    <tbody>
      <tr class="vidbg-type-text">
        <th style="width: 18%">
          <label for="vidbg_metabox_field_container">Container</label>
        </th>
        <td>
          <input type="text" name="vidbg_metabox_field_container" id="vidbg_metabox_field_container" value="<?php echo $container; ?>" />
          <p class="vidbg_metabox_description">Please specify the container you would like your video background in.</p>
          <p class="vidbg_metabox_description">ex: <code>.header</code> or <code>body</code></p>
        </td>
      </tr>
      <tr class="vidbg-type-text">
        <th style="width: 18%">
          <label for="vidbg_metabox_field_mp4">Link to .mp4</label>
        </th>
        <td>
          <input type="text" name="vidbg_metabox_field_mp4" id="vidbg_metabox_field_mp4" value="<?php echo $mp4; ?>" />
          <p class="vidbg_metabox_description">Please specify the link to the .mp4 file. MP4 adds support for Safari &amp; IE.</p>
          <p class="vidbg_metabox_description">ex: <code>http://example.com/header_video.mp4</code></p>
        </td>
      </tr>
      <tr class="vidbg-type-text">
        <th style="width: 18%">
          <label for="vidbg_metabox_field_webm">Link to .webm</label>
        </th>
        <td>
          <input type="text" name="vidbg_metabox_field_webm" id="vidbg_metabox_field_webm" value="<?php echo $webm; ?>" />
          <p class="vidbg_metabox_description">Please specify the link to the .webm file. WEBM adds support for Chrome, Firefox, &amp; Opera.</p>
          <p class="vidbg_metabox_description">ex: <code>http://example.com/header_video.webm</code></p>
        </td>
      </tr>
      <tr class="vidbg-type-text">
        <th style="width: 18%">
          <label for="vidbg_metabox_field_poster">Link to fallback image</label>
        </th>
        <td>
          <input type="text" name="vidbg_metabox_field_poster" id="vidbg_metabox_field_poster" value="<?php echo $poster; ?>" />
          <p class="vidbg_metabox_description">Please specify the link to the fallback image in case your browser does not support Video Background</p>
          <p class="vidbg_metabox_description">ex: <code>http://example.com/header_video.jpg</code></p>
        </td>
      </tr>
    </tbody>
    <tbody class="advanced-options">
      <tr class="vidbg-type-text">
        <th style="width: 18%">
          <label for="vidbg_metabox_field_overlay">Overlay</label>
        </th>
        <td>
          <input type="checkbox" name="vidbg_metabox_field_overlay" id="vidbg_metabox_field_overlay" <?php checked( $overlay, 'on' ); ?> />
          <p class="vidbg_metabox_description">Add an overlay over the video. This is useful if your text isn&#39;t so clear with a video background.</p>
        </td>
      </tr>
      <tr class="vidbg-type-text">
        <th style="width: 18%">
          <label for="vidbg_metabox_field_no_loop">Turn Off Loop?</label>
        </th>
        <td>
          <input type="checkbox" name="vidbg_metabox_field_no_loop" id="vidbg_metabox_field_no_loop" <?php checked( $noloop, 'on' ); ?> />
          <p class="vidbg_metabox_description">Turn off the loop for Video Background. Once the video is fully played it will display the last frame of the video.</p>
        </td>
      </tr>
      <tr class="vidbg-type-text">
        <th style="width: 18%">
          <label for="vidbg_metabox_field_unmute">Play the Audio</label>
        </th>
        <td>
          <input type="checkbox" name="vidbg_metabox_field_unmute" id="vidbg_metabox_field_unmute" <?php checked( $unmute, 'on' ); ?> />
          <p class="vidbg_metabox_description">Enabling this will play the audio of the video.</p>
        </td>
      </tr>
    </tbody>
    <tbody>
      <tr class="vidbg-type-text">
        <th style="width: 18%">
          <label></label>
        </th>
        <td>
          <p class="vidbg_metabox_description"><a href="#advanced-options-panel" class="advanced-options-panel">Advanced Options &raquo;</a></p>
        </td>
      </tr>
    </tbody>
  </table>

  </p>
  <?php
}

/**
 * Save the fields
 */
function vidbg_meta_box_save( $post_id ) {
  // Bail if we're doing an auto save
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

  // if our nonce isn't there, or we can't verify it, bail
  if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

  // if our current user can't edit this post, bail
  if( !current_user_can( 'edit_posts' ) ) return;

  // now we can actually save the data
  $allowed = array(
    'a' => array( // on allow a tags
      'href' => array() // and those anchors can only have href attribute
    )
  );

  //Make sure data is set
  if( isset( $_POST['vidbg_metabox_field_container'] ) )
    update_post_meta( $post_id, 'vidbg_metabox_field_container', wp_kses( $_POST['vidbg_metabox_field_container'], $allowed ) );

  if( isset( $_POST['vidbg_metabox_field_mp4'] ) )
    update_post_meta( $post_id, 'vidbg_metabox_field_mp4', wp_kses( $_POST['vidbg_metabox_field_mp4'], $allowed ) );

  if( isset( $_POST['vidbg_metabox_field_webm'] ) )
    update_post_meta( $post_id, 'vidbg_metabox_field_webm', wp_kses( $_POST['vidbg_metabox_field_webm'], $allowed ) );

  if( isset( $_POST['vidbg_metabox_field_poster'] ) )
    update_post_meta( $post_id, 'vidbg_metabox_field_poster', wp_kses( $_POST['vidbg_metabox_field_poster'], $allowed ) );

  $chk = ( isset( $_POST['vidbg_metabox_field_overlay'] ) && $_POST['vidbg_metabox_field_overlay'] ) ? 'on' : 'off';
  update_post_meta( $post_id, 'vidbg_metabox_field_overlay', $chk );

  $chk2 = ( isset( $_POST['vidbg_metabox_field_no_loop'] ) && $_POST['vidbg_metabox_field_no_loop'] ) ? 'on' : 'off';
  update_post_meta( $post_id, 'vidbg_metabox_field_no_loop', $chk2 );

  $chk3 = ( isset( $_POST['vidbg_metabox_field_unmute'] ) && $_POST['vidbg_metabox_field_unmute'] ) ? 'on' : 'off';
  update_post_meta( $post_id, 'vidbg_metabox_field_unmute', $chk3 );
}
add_action( 'save_post', 'vidbg_meta_box_save' );

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
