<?php

if(!defined('WPINC')) // MUST have WordPress.
    exit('Do NOT access this file directly: '.basename(__FILE__));

    // require_once( 'titan-framework-checker.php' );
    // require_once( 'titan-framework-options.php' );
    // require_once( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' );

// require_once( plugin_dir_path( __FILE__ ) . '/inc/class.WooCommerceFunnel.php' );
// require_once( plugin_dir_path( __FILE__ ) . '/inc/wc-api-custom-meta.php' );
// require_once( plugin_dir_path( __FILE__ ) . '/inc/class.DB_TASKS.php' );
// require_once( plugin_dir_path( __FILE__ ) . '/inc/class.DB_TASKS.php' );

// if ( ! class_exists( 'WP_List_Table' ) ) {
//   require_once( ABSPATH . 'wp-admin/includes/class-wp-screen.php' );//added
//   require_once( ABSPATH . 'wp-admin/includes/screen.php' );//added
//   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
//   require_once( ABSPATH . 'wp-admin/includes/template.php' );
// }

require_once( plugin_dir_path( __FILE__ ) . '/inc/class.custom_survey_tracker.php' );


add_action( 'plugins_loaded', function () {

	Custom_Survey_Tracker::get_instance();

} );
