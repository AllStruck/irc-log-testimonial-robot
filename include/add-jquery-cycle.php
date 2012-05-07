<?php
/* */


function my_jquery_register() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}    
add_action('wp_enqueue_scripts', 'my_jquery_register');

function my_jquery_cycle_register() {
    wp_deregister_script( 'jquery-cycle' );
    wp_register_script( 'jquery-cycle', plugin_dir_url(__FILE__) . '../jquery.cycle.all.js');
    wp_enqueue_script( 'jquery-cycle' );
}    
add_action('wp_enqueue_scripts', 'my_jquery_cycle_register');

function iltr_cycle_head_init() {
	echo "	
	<script type=\"text/javascript\">
$(document).ready(function() {





    $('#homeTestimonialSlider').cycle({
        fx: 'fadeZoom',
        delay: -3000
    });
    
    
    
    
    
});


/* This doesn't work but I want it to:
$(document).ready(function() {



	$.fn.cycle.transitions.scrollUp = function($cont, $slides, opts) { 
	        $cont.css('overflow','hidden'); 
	        opts.before.push($.fn.cycle.commonReset); 
	        var h = $cont.height(); 
	        opts.cssBefore ={ top: h, left: 0 }; 
	        opts.cssFirst = { top: 0 }; 
	        opts.animIn	  = { top: 0 }; 
	        opts.animOut  = { top: -h }; 
	}; 
	
	$('#ILTRtestimonialSlider').cycle({
					fx: 'scrollUp'
	});
	

});
*/
</script>
	";
}
add_action('wp_head', 'iltr_cycle_head_init');
?>