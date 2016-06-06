jQuery( document ).ready(function($) {

	// show advanced options on click
	$(function() {
		$('#vidbg_advanced_options').hide();
	  $(".cmb2-id-vidbg-metabox-field-advanced-button a").click(function() {
			if ($('#vidbg_advanced_options').css('display') === 'none') {
	    	$('#vidbg_advanced_options').show(500);
				$('a.advanced-options-button').text('Hide Advanced Options');
			} else {
				$('#vidbg_advanced_options').hide(500);
			}
	  });
	});

	// show extra overlay settings if enabled
  $(function(){
    $('#vidbg_metabox_field_overlay1, #vidbg_metabox_field_overlay2').bind('change', function (e) {
      if( $('#vidbg_metabox_field_overlay1').is(':checked')) {
        $('.cmb2-id-vidbg-metabox-field-overlay-color, .cmb2-id-vidbg-metabox-field-overlay-alpha').hide(500);
      }
      else if( $('#vidbg_metabox_field_overlay2').is(':checked')) {
        $('.cmb2-id-vidbg-metabox-field-overlay-color, .cmb2-id-vidbg-metabox-field-overlay-alpha').show(500);
      }
    }).trigger('change');
  });

	// Loop through all cmb-type-slider-field instances and instantiate the slider UI
	$( '.cmb-type-own-slider' ).each(function() {
		var $this       = $( this );
		var $value      = $this.find( '.own-slider-field-value' );
		var $slider     = $this.find( '.own-slider-field' );
		var $text       = $this.find( '.own-slider-field-value-text' );
		var $range			= $this.find( '.ui-slider-range' );
		var slider_data = $value.data();

		$slider.slider({
			range 	: 'min',
			value 	: slider_data.start,
			min   	: slider_data.min,
			animate : 'fast',
			max   	: slider_data.max,
			slide 	: function( event, ui ) {
				$value.val( ui.value );
				$text.text( ui.value );
			}
		});

		// Initiate the display
		$value.val( $slider.slider( 'value' ) );
		$text.text( $slider.slider( 'value' ) );

		$this.css({
			'visibility': 'visible',
		});
	});


	// Ajax used to permanently dismiss premium notice message
	jQuery(document).on( 'click', '.vidbg-premium-notice .notice-dismiss', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'vidbg_dismiss_premium_notice'
        }
    })
	})

});
