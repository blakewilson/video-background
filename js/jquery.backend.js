jQuery( document ).ready(function($) {

	$(function() {                       				//run when the DOM is ready
	  $(".advanced-options-panel").click(function() {   //use a class, since your ID gets mangled
	    $(".advanced-options").toggle("slow");      	//add the class to the clicked element
	  });
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
