<?php 
/*
*/

// Set up PHP constants if not already available (WP < 3.0 compliant)
 if ( ! defined( 'WP_CONTENT_URL' ) )
       define( 'WP_CONTENT_URL', WP_SITEURL . '/wp-content' );
 if ( ! defined( 'WP_CONTENT_DIR' ) )
       define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
 if ( ! defined( 'WP_PLUGIN_URL' ) )
       define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
 if ( ! defined( 'WP_PLUGIN_DIR' ) )
       define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
 if ( ! defined( 'WPMU_PLUGIN_URL' ) )
       define( 'WPMU_PLUGIN_URL', WP_CONTENT_URL. '/mu-plugins' );
 if ( ! defined( 'WPMU_PLUGIN_DIR' ) )
       define( 'WPMU_PLUGIN_DIR', WP_CONTENT_DIR . '/mu-plugins' );

 ?>