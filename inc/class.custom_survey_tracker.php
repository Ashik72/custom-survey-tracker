<?php

if(!defined('WPINC')) // MUST have WordPress.
    exit('Do NOT access this file directly: '.basename(__FILE__));

/**
 * Admin Menu
 */
class Custom_Survey_Tracker {

		static $instance;

		/** Singleton instance */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


    public function __construct() {
        //add_action( 'admin_menu', array( $this, 'admin_menu' ) );

        //d(dirname(dirname( __FILE__ )));

    	add_action( 'wp_enqueue_scripts', array($this, 'load_custom_wp_frontend_style') );
    	add_action( 'init', [$this, 'custom_post_type'] );
        add_action( 'wp_ajax_custom_survey_tracker', array($this, 'plugin_data_custom_survey_tracker_func') );
	    add_action( 'wp_ajax_nopriv_custom_survey_tracker', array($this, 'plugin_data_custom_survey_tracker_func') );

		add_action( 'add_meta_boxes', array($this, 'add_events_metaboxes') );

    }


	function add_events_metaboxes() {
		add_meta_box('show_stat', 'Statistics', [$this, 'survey_stats_func'], 'survey_stats', 'normal', 'high');
	}

	public function survey_stats_func() {

		$post_id = get_the_ID();

		if (empty($post_id))
			return;

		ob_start();

		$get_answered_ques = get_post_meta( $post_id, 'answered_ques', true );
		$get_progress = get_post_meta( $post_id, 'progress', true );
		$get_user_data = get_post_meta( $post_id, 'user_data', true );

		$html = "";

		$html .= "<div class='ans_ques'><div><h2><strong>Answered Questions:</strong></h2></div>";

		$html .= "<div>";

		$get_answered_ques = ( is_array($get_answered_ques) ? $get_answered_ques : array());

		foreach ($get_answered_ques as $key => $question) {
			$html .= $question;
			$html .= "<br>";
		}
		
		$html .= "</div>";
		$html .= "</div>";

		$html .= "<div class='survey_progress'><div><h2><strong>Survey Progress: ".$get_progress."</strong></h2></div>";

		$html .= "<div class='user_data'><div><h2><strong>User Data:</strong></h2></div>";

		$html .= "<div>";

		$get_user_data = ( is_array($get_user_data) ? $get_user_data : array());

		foreach ($get_user_data as $key => $question) {
			$html .= $key." : ".$question;
			$html .= "<br>";
		}
		
		$html .= "</div>";
		$html .= "</div>";


		_e($html);
		$output = ob_get_clean();

		_e($output);

	}

    public function load_custom_wp_frontend_style() {

    	wp_register_script( 'custom_survey_tracker-script', custom_survey_tracker_PLUGIN_URL.'script_custom.js', array( 'jquery' ), '', true );

        wp_localize_script( 'custom_survey_tracker-script', 'plugin_data_custom_survey_tracker', array( 'ajax_url' => admin_url('admin-ajax.php') ));

        wp_enqueue_script( 'custom_survey_tracker-script' );

        wp_enqueue_style( 'custom_survey_tracker_style', custom_survey_tracker_PLUGIN_URL.'style.css' );



    }

    public function custom_post_type() {

    $labels = array(
        'name'               => _x( 'Survey Stats', 'post type general name', 'my-textdomain' ),
        'singular_name'      => _x( 'Survey Stat', 'post type singular name', 'my-textdomain' ),
        'add_new'            => _x( 'Add A Stat', 'book', 'my-textdomain' ),
        'add_new_item'       => __( 'Add New Stat', 'my-textdomain' ),
        'new_item'           => __( 'New Stat', 'my-textdomain' ),
        'edit_item'          => __( 'Edit Stat', 'my-textdomain' ),
        'view_item'          => __( 'View Stat', 'my-textdomain' ),
        'all_items'          => __( 'All Stats', 'my-textdomain' ),
        'search_items'       => __( 'Search Stat', 'my-textdomain' ),
        'not_found'          => __( 'Not statistic found!', 'my-textdomain' ),
        'not_found_in_trash' => __( 'Nothing on trash', 'my-textdomain' )
    );


    $args = array(
        'public'                => true,
        'menu_icon'             => 'dashicons-building',
        'exclude_from_search'   => true,
        'supports'              => array( 'title', 'custom-fields' ),
        'label'                 => 'Survey Stats',
        'labels'                => $labels,
    );


    register_post_type( 'survey_stats', $args );
}

	public function plugin_data_custom_survey_tracker_func() {

		if (empty($_POST['survey_id']))
			wp_die();

		if (empty($_POST['question']))
			wp_die();

		if( session_id() == '' )
			session_start();
	
		global $wpdb;

		$survey_ids = $_POST['survey_id'];

		$survey_ids = explode("-", $survey_ids);

		if (count($survey_ids) < 3)
			wp_die();

		$survey_id = (int) $survey_ids[1];
		$unique_id = (int) $survey_ids[2];

		$sql_get_survey = "SELECT * FROM `".$wpdb->base_prefix."modal_survey_surveys` WHERE id = {$survey_id} LIMIT 1";
		$sql_get_survey = $wpdb->get_results( $sql_get_survey );

		if (empty($sql_get_survey[0]))
			wp_die();

		$sql_get_survey = $sql_get_survey[0];

		$survey_title_clean = $this->clean($sql_get_survey->name);

		$survey_title_clean = $survey_title_clean."-".$unique_id;

		$post_id = $this->doPost($survey_title_clean, $unique_id);

		if (empty($post_id))
			wp_die();

		$post_id = (int) $post_id;

		$get_answered_ques = get_post_meta( $post_id, 'answered_ques', true );

		$get_answered_ques = ( ( !is_array($get_answered_ques) || empty($get_answered_ques) ) ? array() : $get_answered_ques );

		$question = $_POST['question'];
		$progress = $_POST['progress'];

		array_push($get_answered_ques, $question);

		update_post_meta($post_id, 'answered_ques', $get_answered_ques);
		update_post_meta($post_id, 'progress', $progress);

		$get_answered_ques = get_post_meta( $post_id, 'answered_ques', true );
		$get_progress = get_post_meta( $post_id, 'progress', true );

		if (is_user_logged_in())
			$this->logged_in_data($post_id);
		else
			$this->not_logged_in_data($post_id);

		$get_user_data = get_post_meta( $post_id, 'user_data', true );

		echo json_encode(['answered_ques' => $get_answered_ques, 'progress' => $get_progress, 'user_data' => $get_user_data]);

		wp_die();

	}

	private function logged_in_data($post_id) {

		if (empty($post_id))
			return;

		$user_data = array();
		$user_id = get_current_user_id();

		$user_data['user_id'] = $user_id;

		$user_info = get_userdata($user_id);
      	$user_data['username'] = $user_info->user_login;
      	$user_data['first_name'] = $user_info->first_name;
      	$user_data['last_name'] = $user_info->last_name;
      	$user_data['user_email'] = $user_info->user_email;

      	update_post_meta($post_id, 'user_data', $user_data);

	}

	private function not_logged_in_data($post_id) {

		if (empty($post_id))
			return;

		$user_data = array();

		$user_data['ip'] = $this->get_client_ip_env();

      	update_post_meta($post_id, 'user_data', $user_data);

	}

	private function get_client_ip_env() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';

	return $ipaddress;
}



	private function doPost($title, $uid) { 

		if (empty($title))
			return;

		$page = get_page_by_title( wp_strip_all_tags( $title ), 'OBJECT', 'survey_stats');

		if (!empty($page->ID))
			return $page->ID;

		$my_post = array(
		  'post_title'    => wp_strip_all_tags( $title ),
		  'post_content'  => "",
		  'post_status'   => 'publish',
		  'post_type'	=> 'survey_stats'
		);
		 
		$post_id = wp_insert_post( $my_post );

		if (!empty($uid))
			update_post_meta($post_id, 'survey_id', $uid);


		return $post_id;

	}


	public function clean($string) {

	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

	   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

	}

}