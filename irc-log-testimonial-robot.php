<?php
/*
Plugin Name: IRC Log Testimonial Robot
Plugin URI: http://irc-log-testimonial-robot.allstruck.com
Description: Periodically fetches any messages found in IRC chat log search website matching a Nick + "Thanks" found in the message, and adds these messages in the custom post type Testimonials with one or more Venues set.
Author: AllStruck
Version: 1.0
*/
ini_set("memory_limit","256M");

require_once('controller/init.php');
require_once('library/simple_html_dom.php');


// Debug stuffs
$debug = true;
$debug_verbosity = 5;
function dbgprnt($text="", $level=10) {
	global $debug_verbosity;
	if ($level >= $debug_verbosity) {
		global $debug;
		if ($debug) { _e("<p>$text</p>"); }
	}
}


require_once('view/settings.php');
require_once('controller/add-jquery-cycle.php');
require_once('controller/new-message-robot.php');




?>