<?php

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}


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
		'type' => 'checkbox',
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
		'type' => 'text',
	) );

	$vidbg_metabox->add_field( array(
		'name' => __( 'Turn off loop?', 'video-background' ),
		'desc' => __( 'Turn off the loop for Video Background. Once the video is complete, it will display the last frame of the video.', 'video-background' ),
		'id'   => $prefix . 'no_loop',
		'type' => 'checkbox',
	) );

	$vidbg_metabox->add_field( array(
		'name' => __( 'Play the audio?', 'video-background' ),
		'desc' => __( 'Enabling this will play the audio of the video.', 'video-background' ),
		'id'   => $prefix . 'unmute',
		'type' => 'checkbox',
	) );


}
add_action( 'cmb2_admin_init', 'vidbg_register_metabox' );
