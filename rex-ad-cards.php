<?php
/*
Plugin Name: Rex Ad Cards
Description: Display ads you created on https://www.rexadcards.com/ to promote your own products or those of your sponsors.
Version:     1.0
Author:      Rex Ad Cards
Author URI:  https://www.rexadcards.com/
License:     GPL2
 
Rex Ad Cards is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Rex Ad Cards is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Rex Ad Cards. If not, see http://www.gnu.org/licenses/.
*/

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

function rac_add_settings_submenu() {
	add_options_page( 'Rex Ad Cards Settings', 'Rex Ad Cards', 'manage_options', 'rex-ad-cards', 'rac_settings_submenu_callback' );
}

function rac_settings_submenu_callback() {
	$changes_saved = false;
	
	if ( isset( $_POST['rac-code-snippet'] ) ) {
		// Update options
		$rac_snippet = rac_sanitize( $_POST['rac-code-snippet'] );
		$rac_api_key = sanitize_text_field( rac_extract_api_key( $rac_snippet ) );
		$rac_snippet_code = sanitize_text_field( rac_extract_snippet_code( $rac_snippet ) );
		
		update_option( 'rac_api_key', $rac_api_key, true );
		update_option( 'rac_snippet_code', $rac_snippet_code, true );
		
		$changes_saved = true;
	} elseif ( isset( $_POST['submit_change'] ) ) {
		// Clear options
		update_option( 'rac_api_key', '', true );
		update_option( 'rac_snippet_code', '', true );
	}
	
	$rac_api_key = sanitize_text_field( get_option( 'rac_api_key', '' ) );
	$rac_snippet_code = sanitize_text_field( get_option( 'rac_snippet_code', '' ) );
	
	require_once( plugin_dir_path( __FILE__ ) . 'views/settings.php' );
}
add_action( 'admin_menu', 'rac_add_settings_submenu' );


function rac_load_admin_css( $hook ) {
	if ( $hook == 'settings_page_rex-ad-cards' )
		wp_enqueue_style( 'rac_admin_css', plugins_url( 'css/admin.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'rac_load_admin_css' );


function rac_enqueue() {
	wp_enqueue_style( 'rac_code_snippet_missing_style', plugins_url( 'css/code-snippet-missing.css', __FILE__ ) );
	
	$rac_api_key = sanitize_text_field( get_option( 'rac_api_key', '' ) );
	$rac_snippet_code = sanitize_text_field( get_option( 'rac_snippet_code', '' ) );
	
	if ( !empty( $rac_api_key ) && !empty( $rac_snippet_code ) ) {
		wp_enqueue_style( 'rac_style', 'https://www.rexadcards.com/rex-ad-cards-sdk/rex-ad-cards.css' );
		wp_enqueue_script( 'rac_script', 'https://www.rexadcards.com/rex-ad-cards-sdk/rex-ad-cards.js' );

		wp_add_inline_script(
			'rac_script',
			'var config = {
				apiKey: "' . esc_attr( $rac_api_key ) . '",
				snippet_code : "' . esc_attr( $rac_snippet_code ) . '"
			};
			RCC._init(config)'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'rac_enqueue' );


function rac_shortcode_callback() {
	$rac_snippet_code = sanitize_text_field( get_option( 'rac_snippet_code', '' ) );
	
	if ( empty($rac_snippet_code) ) {
		if ( current_user_can('administrator') ) {
		
			ob_start();
			require( plugin_dir_path( __FILE__ ) . 'views/code-snippet-missing.php' );
			$rac_snippet_code_missing = ob_get_contents();
			ob_end_clean();
			
			return do_shortcode( $rac_snippet_code_missing );
		} else
			return '';
	} else {
		return do_shortcode( '<div id="' . esc_attr( $rac_snippet_code ) . '"></div>' );
	}
}
add_shortcode( 'rex-ad-cards-1', 'rac_shortcode_callback' );


function rac_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=rex-ad-cards">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'rac_add_settings_link' );


add_filter( 'widget_text', 'do_shortcode' );


function rac_sanitize( $input ) {
	if ( !is_string( $input ) )
		return '';
	
	if ( mb_strlen( $input ) > 4096 )
		return '';
	
	$input = stripslashes( trim( $input ) );
	
	return $input;
}


function rac_extract_api_key( $input ) {
	$result = "";
	$api_key_strpos = strpos( $input, 'apiKey' );
	if ( $api_key_strpos !== FALSE ) {
		$api_key_strpos += 9;
		$api_key_strpos_end = strpos( $input, '"', $api_key_strpos);
		if ( $api_key_strpos_end !== FALSE ) {
			$find_result = substr($input, $api_key_strpos, $api_key_strpos_end - $api_key_strpos);
			if ( $find_result !== FALSE )
				$result = $find_result;
		}
	}
	return $result;
}


function rac_extract_snippet_code( $input ) {
	$result = "";
	$snippet_code_strpos = strpos( $input, 'snippet_code' );
	if ( $snippet_code_strpos !== FALSE ) {
		$snippet_code_strpos += 16;
		$snippet_code_strpos_end = strpos( $input, '"', $snippet_code_strpos);
		if ( $snippet_code_strpos_end !== FALSE ) {
			$find_result = substr($input, $snippet_code_strpos, $snippet_code_strpos_end - $snippet_code_strpos);
			if ( $find_result !== FALSE )
				$result = $find_result;
		}
	}
	return $result;
}