<?php
/* */


// Action sequence
$directorSaysAction = (isset($_GET['irc']) && $_GET['irc'] == 'go');
$messageNotYetTestimonial = true;


if ($directorSaysAction) {
	dbgprnt("Director said action let's go!");
	add_action('init', 'check_for_messages_and_add_new_testimonials');
} else {
	dbgprnt("Hold your places, we're not moving anything yet. DIRECTOR OFF SET!", 1);
}
function check_for_messages_and_add_new_testimonials() {
	dbgprnt("First step: pulling up messages from IRC for nick.");
	$chanlogRoot = 'https://irclogs.wordpress.org/chanlog.php/';
	$url = $chanlogRoot . '?channel=wordpress&search=allstruck&sort=asc';
	global $messageNotYetTestimonial;
	
	$response = wp_remote_get( $url, array(
		'method' => 'GET',
		'timeout' => 14,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array( ),
		'body' => array( ),
		'cookies' => array(),
		'sslverify' => false
	    )
	);
	
	
	if( is_wp_error( $response ) || !isset($response)) {
	   dbgprnt('HTTP Request FUBAR! \$response was error or not set!');
	} else {
		dbgprnt('HTTP Loaded Okay and now we will start inspecting..');
		$html = new simple_html_dom();
		  
		// Load from a string  
		$html->load($response['body']); 
		dbgprnt("Loaded the body of returned page..");
		
		$elements = $html->find("ul.entry");
		
		if (count($elements) < 1) {
			dbgprnt("There aren't any UL.entry elements found in the body here :|");
		}
		
		foreach ($elements as $element) {
			$innertext = $element->innertext;
			dbgprnt("Pulled out a ul.entry element from the page body, Performing additional checks on message for juicy stuff.");
			$messageContainsPraiseWord = (strripos($innertext, 'Thank') || 
										strripos($innertext, 'thx') || 
										strripos($innertext, 'genius') || 
										strripos($innertext, 'brilliant'));
			$messageIsUsable = !(strripos($innertext, 'Thanks anyway') || 
								strripos($innertext, 'Thanks though') || 
								strripos($innertext, 'fuck') || 
								strripos($innertext, 'cunt') || 
								strripos($innertext, 'whore'));
			
			// If contains praise word and is usable...
			if ($messageContainsPraiseWord && $messageIsUsable) {
				
				dbgprnt("This message is very juicy so we're going to pull it apart for storage.");
				$html = new simple_html_dom();

				$messageBlock = "<ul class='entry'>$innertext</ul>";
				// Initialize Simple HTML DOM class.
				dbgprnt("Loading message block into Simple HTML DOM.");
				$html->load($messageBlock);
				
				// Pull out individual 
				$messageID = $html->find('li.ts a[href]', 0); $messageID = $chanlogRoot . $messageID->href;
				$messageTime = $html->find('li.ts a[href]', 0);$messageTime = $messageTime->plaintext;
				$messageAuthor = $html->find('li.nick a', 0); $messageAuthor = $messageAuthor->innertext;
				$messageBody = $html->find('li.msg', 0); $messageBody = $messageBody->plaintext;
				
				dbgprnt("MessageID: $messageID");
				dbgprnt("MessageTime: $messageTime");
				dbgprnt("MessageAuthor: $messageAuthor");
				dbgprnt("MessageBody: $messageBody");
				dbgprnt("");
				
				
				//Check to see if message has been added already by checking messageID against external-id.
				global $testimonialIDQuery;
				global $wpdb;
				global $post;
				global $messageNotYetTestimonial;
				
				dbgprnt("Pulling up all post_meta with wpcf-external-id matching $messageID");
				$testimonialIDQuery = new WP_Query( array('post_type' => 'testimonial',
														'meta_key' => 'wpcf-external-id', 
														'meta_value' => "$messageID",
														 ));
				print_r($testimonialIDQuery);
				global $messageNotYetTestimonial;
				$messageNotYetTestimonial = true;
				if ($testimonialIDQuery->post_count > 0) {
					dbgprnt("Looks like we found some matching post_meta, let's make sure it's not just a remnant");
					while ( $testimonialIDQuery->have_posts() ) : $testimonialIDQuery->the_post();
						global $wpdb;
						$existing_post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE id = '" . $post->ID . "'");
						if ($existing_post != null) {
							global $messageNotYetTestimonial;
							$messageNotYetTestimonial = false;
							$matchedID = $post->ID;
							dbgprnt("Existing post found $matchedID in wp_posts, \$messageNotYetTestimonial set to false");
							print_r($existing_post);
						}
					endwhile;
				}
				
				// Create new testimonial using this message if it hasn't already been added.
				if ($messageNotYetTestimonial) {
					dbgprnt("About to try adding testimonial <br />");
					// Add this message to testimonials with data and add freenode.net#wordpress as venue.
					// First add new testimonial with title of first 7 words, and entire message in the_content.
					$testimonialTitle = explode(' ', $messageBody, 7);
					if (count($testimonialTitle) > 6) {$testimonialRemainderJunk = array_pop($testimonialTitle);}
					$testimonialTitle = implode(' ', $testimonialTitle) . '..';
					$testimonialDate = date('Y-m-d H:i:s', strtotime($messageTime, strtotime("Now " . get_option('gmt_offset'))));
					$testimonialDateGMT = date('Y-m-d H:i:s', strtotime($messageTime));
					$testimonialAddedDate = date('Y-m-d H:i:s', strtotime('Now'));
					  $newTestimonialPost = array(
					     'post_title' => $testimonialTitle,
					     'post_content' => $messageBody,
					     'post_status' => 'publish',
					     'post_author' => 1,
					     'post_type' => 'testimonial',
					     'post_date' => $testimonialDate,
					     'post_date_gmt' => $testimonialDateGMT,
					     'post_modified' => $testimonialAddedDate
					  );
					$newTestimonialPostID = wp_insert_post($newTestimonialPost);
					$newTestimonialPost['ID'] = $newTestimonialPostID;
					wp_update_post($newTestimonialPost);
					
					if ($newTestimonialPostID > 0) {
						dbgprnt("Added post ID: $newTestimonialPostID with date: $testimonialDate<br/>");
						// Next add custom meta information (author and external-id).
						add_post_meta($newTestimonialPostID, 'wpcf-author', $messageAuthor, true);
						add_post_meta($newTestimonialPostID, 'wpcf-external-id', $messageID, true);
						add_post_meta($newTestimonialPostID, 'wpcf-url', $messageID, true);
						// Then add freenode.net#wordpress venue
						wp_set_object_terms( $newTestimonialPostID, 'freenode-net-wordpress', 'venue', true );
					} else {
						dbgprnt("Error: no new post was created for some reason. $newTestimonialPostID <br/>");
					}
				} else {
					dbgprnt("Skipped message, already exists. Message: $messageID Testimonial: <br/>");
					//print_r($existing_posts);
				}
				wp_reset_postdata();
			} else {
				dbgprnt("This message was poring and so kaput we throw away like road kill.");
			}
		}
	}
}



?>