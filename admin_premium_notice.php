<?php
/**
 * Create the premium notice message
 */
function vidbg_premium_notice() {
	$class = 'notice notice-success vidbg-premium-notice is-dismissible';
	$message = __( 'Does a version of Video Background with premium features interest you? <a href="http://blakewilson.me/projects/video-background/premium-video-background/?utm_source=videobackgroundwordpress&utm_medium=wordpressnotice&utm_campaign=Video%20Background%20Wordpress%20Premium%20Notice" target="_blank">Please take 2 seconds to let me know!</a>', 'video-background' );
  $is_dismissed = get_option( 'vidbg-premium-notice-dismissed' );

  if( empty( $is_dismissed ) ) {
	   printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
  }
}
add_action( 'admin_notices', 'vidbg_premium_notice' );



/**
 * Ajax handler to permanently dismiss notice
 */
function vidbg_dismiss_premium_notice() {
	update_option( 'vidbg-premium-notice-dismissed', 1 );
}
add_action( 'wp_ajax_vidbg_dismiss_premium_notice', 'vidbg_dismiss_premium_notice' );



/**
 * Restore permanently dismissed premium notice message
 */
function vidbg_restore_premium_notice() {
  /* delete_option( 'vidbg-premium-notice-dismissed' ); */
}
add_action( 'admin_init', 'vidbg_restore_premium_notice' );
?>
