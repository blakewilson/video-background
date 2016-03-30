jQuery( document ).ready(function($) {

	// show advanced options on click
	$(function() {
		$('.cmb2-id-vidbg-metabox-field-overlay, .cmb2-id-vidbg-metabox-field-overlay-color, .cmb2-id-vidbg-metabox-field-overlay-alpha, .cmb2-id-vidbg-metabox-field-no-loop, .cmb2-id-vidbg-metabox-field-unmute').hide();
	  $(".cmb2-id-vidbg-metabox-field-advanced a").click(function() {
	    $('.cmb2-id-vidbg-metabox-field-overlay, .cmb2-id-vidbg-metabox-field-overlay-color, .cmb2-id-vidbg-metabox-field-overlay-alpha, .cmb2-id-vidbg-metabox-field-no-loop, .cmb2-id-vidbg-metabox-field-unmute').show();
			$('.cmb2-id-vidbg-metabox-field-advanced').hide();
	  });
	});

	// show extra overlay settings if enabled
  $(function(){
    $('#vidbg_metabox_field_overlay1, #vidbg_metabox_field_overlay2').bind('change', function (e) {
      if( $('#vidbg_metabox_field_overlay1').is(':checked')) {
        $('.cmb2-id-vidbg-metabox-field-overlay-color, .cmb2-id-vidbg-metabox-field-overlay-alpha').hide();
      }
      else if( $('#vidbg_metabox_field_overlay2').is(':checked')) {
        $('.cmb2-id-vidbg-metabox-field-overlay-color, .cmb2-id-vidbg-metabox-field-overlay-alpha').show();
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

		$('.ui-slider-range, .ui-slider-handle').each(function() {
			$(this).addClass( 'button-primary' );
		});

		$this.css({
			'visibility': 'visible',
		});
	});

});
