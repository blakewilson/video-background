<?php
/**
 * Create the premium notice message
 */
function vidbg_premium_notice() {
	$class = 'notice notice-success vidbg-premium-notice is-dismissible';
	$message = __( 'Youtube, Visual Composer, and SiteOrigin Integration with much more in the new Video Background Pro! <a href="http://pushlabs.co/video-background-pro" target="_blank">Learn more &raquo;</a>', 'video-background' );
  $is_dismissed = get_option( 'vidbgpro-notice-dismissed' );

  if( empty( $is_dismissed ) ) {
	   printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
  }
}
add_action( 'admin_notices', 'vidbg_premium_notice' );



/**
 * Ajax handler to permanently dismiss notice
 */
function vidbg_dismiss_premium_notice() {
	update_option( 'vidbgpro-notice-dismissed', 1 );
}
add_action( 'wp_ajax_vidbg_dismiss_premium_notice', 'vidbg_dismiss_premium_notice' );
?>
