<?php
/**
 * Leadenhall Theme Customizer.
 *
 * @package Leadenhall
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function leadenhall_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	//1. Define a new section (if desired) to the Theme Customizer
	$wp_customize->add_section( 'leadenhall_custom_code',
		 array(
				'title' => __( 'Custom Code', 'leadenhall' ), //Visible title of section
				//'priority' => 35, //Determines what order this appears in
				'capability' => 'edit_theme_options', //Capability needed to tweak
				'description' => __('Allows you to customize some settings for Leadenhall.', 'leadenhall'), //Descriptive tooltip
		 )
	);
	//2. Register new settings to the WP database...
	$wp_customize->add_setting( 'analytics_code', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
	 array(
			'default' => '', //Default setting/value to save
			'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
			'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
			'transport' => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
	 )
	);
	//3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
	$wp_customize->add_control( new WP_Customize_Control( //Instantiate the control class
		 $wp_customize, //Pass the $wp_customize object (required)
		 'leadenhall_analytics_code', //Set a unique ID for the control
		 array(
				'label' => __( 'Analytics/Tracking Code', 'leadenhall' ), //Admin-visible name of the control
				'section' => 'leadenhall_custom_code', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
				'settings' => 'analytics_code', //Which setting to load and manipulate (serialized is okay)
				'type' => 'textarea',
				'priority' => 10, //Determines the order this control appears in for the specified section
		 )
	) );
}
add_action( 'customize_register', 'leadenhall_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function leadenhall_customize_preview_js() {
	wp_enqueue_script( 'leadenhall_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'leadenhall_customize_preview_js' );
