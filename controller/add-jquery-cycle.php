<?php
/* */




function my_jquery_register() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}    
add_action('wp_enqueue_scripts', 'my_jquery_register');


function my_jquery_cycle_register() {
    wp_deregister_script( 'jquery-cycle' );
    wp_register_script( 'jquery-cycle', WP_PLUGIN_URL . '/view/js/jquery.cycle.all.js');
    wp_enqueue_script( 'jquery-cycle' );
}    
add_action('wp_enqueue_scripts', 'my_jquery_cycle_register');




function iltr_cycle_head_init() {
	echo '<style type="text/css">';
	echo '
	
			.fadeFromAllSides {
				overflow: visible;
				position:relative;
				}
			.testimonialArea {
				overflow: visible;
				}
				
				.fadeFromAllSides p {
				  font: 1.7em/1.1em "RalewayThin";
				  font-weight: lighter;
				  text-align:center;
				  width:100% !important;
				  margin:auto;
				
				}
				.fadeFromAllSides div {
				  padding-top: 1em;
				  width: 100% !important;
				}
			</style>
		';
	echo '<script type="text/javascript">';
echo '
			$(function() {
			
		// Create a new transition type for jQuery Cycle called "fadeFromAllSides"
		$.fn.cycle.transitions.fadeFromAllSides = function($cont, $slides, opts) {
	    var $el = $($slides[0]);
	    var w = $el.width();
	    var h = $el.height();
	    
	    var randomPositionDistanceSeed = 10000;
	    var randomPositionDistanceSeedLong = randomPositionDistanceSeed * 2;
	    
	    
	    var randX = (Math.random()*randomPositionDistanceSeed)-(Math.random()*randomPositionDistanceSeedLong);
	    var randY = (Math.random()*randomPositionDistanceSeed)-(Math.random()*randomPositionDistanceSeedLong);
	    
	    opts.cssBefore = { top: randX, left: randY, display: "block", opacity: 0, position: "absolute", zIndex: 1 };
	    opts.animIn    = { top: 0, left: 0, opacity: 1 };
	    opts.animOut   = { opacity: 0 };
	    opts.cssAfter  = { zIndex: 0 };
	    if (!opts.speedAdjusted) {
	        opts.speed = opts.speed / 2;
	        opts.speedAdjusted = true;
	    }
		};


		    
		    $(".fadeFromAllSides").cycle({
		    	fx: "fadeFromAllSides",
		    	speed: 5000
	    	});
			});
		

	</script>
';
}
add_action('wp_footer', 'iltr_cycle_head_init', 1);
?>