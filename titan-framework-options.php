<?php

if (!defined('ABSPATH'))
  exit;


add_action( 'tf_create_options', 's2_sensei_custom_options_wc_funnel_nci', 150 );

function s2_sensei_custom_options_wc_funnel_nci() {


	$titan = TitanFramework::getInstance( 'wc_funnel_nci_opts' );
	$section = $titan->createAdminPanel( array(
		    'name' => __( 'WooCommerce Funnel Integration', 'wc_funnel_nci' ),
		    'icon'	=> 'dashicons-feedback'
		) );

	$tab = $section->createTab( array(
    		'name' =>  __( 'General Options', 'wc_funnel_nci' )
		) );


    $tab->createOption( array(
    'name' => 'WooCommerce Consumer Key',
    'id' => 'wc_cs_key',
    'type' => 'text',
    'desc' => '<a target="_blank" href="'.get_admin_url().'/admin.php?page=wc-settings&tab=api&section=keys">WooCommerce > Settings > API > Keys/Apps</a>'
    ) );

    $tab->createOption( array(
    'name' => 'WooCommerce Consumer Secret',
    'id' => 'wc_cs_secret',
    'type' => 'text',
    'desc' => '<a target="_blank" href="'.get_admin_url().'/admin.php?page=wc-settings&tab=api&section=keys">WooCommerce > Settings > API > Keys/Apps</a>'
    ) );

    $tab->createOption( array(
    'name' => 'WC Funnel Email',
    'id' => 'wc_funnel_mail',
    'type' => 'text',
    'desc' => 'Grab from ...'
    ) );

    $tab->createOption( array(
    'name' => 'WC Funnel Password',
    'id' => 'wc_funnel_password',
    'type' => 'text',
    'desc' => 'Grab from ...'
    ) );

    $tab->createOption( array(
    'name' => 'My Unique Secrect',
    'id' => 'wc_funnel_usecret',
    'type' => 'text',
    'desc' => '<strong>A unique key or phrase.</strong>'
    ) );


    $tab->createOption( array(
    'type' => 'custom',
    'name' => 'Connection Status',
    'custom' => '<div class="wc_funnel_connection_status">Checking...</div>'
    ) );


		$section->createOption( array(
  			  'type' => 'save',
		) );


    $section_2 = $section->createAdminPanel( array(
  		    'name' => __( 'Funnel Design', 'wc_funnel_nci' ),
  		    'icon'	=> 'dashicons-image-filter'
  		) );

      $section_2->createOption( array(
      'name' => 'Funnel Name',
      'id' => 'funnely_title',
      'type' => 'text',
      'desc' => 'Grab from ...'
      ) );

      $section_2->createOption( array(
      'type' => 'iframe',
      'url' => plugin_dir_url(__FILE__).'interface/',
      'height' => 1368
      ) );


		/////////////New

/*		$embroidery_sub = $section->createAdminPanel(array('name' => 'Embroidering Pricing'));


		$embroidery_tab = $embroidery_sub->createTab( array(
    		'name' => 'Profiles'
		) );


		$wp_expert_custom_options['embroidery_tab'] = $embroidery_tab;

		return $wp_expert_custom_options;
*/
}


 ?>
