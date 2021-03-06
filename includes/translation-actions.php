<?php

/*
 * Translations Actions
 *
 * @package     WP-Translations
 * @subpackage  Includes
 * @copyright   Copyright (c) 2017, Sadler Jérôme
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Processes all actions sent via POST and GET by looking for the 'wp-translations-action'
 * request and running do_action() to call the function
 *
 * @since 1.0
 * @return void
 */
function wp_translations_process_actions() {

	if ( isset( $_POST['wp-translations-action'] ) ) {
		do_action( 'wp_translations_' . $_POST['wp-translations-action'], $_POST );
	}

	if ( isset( $_GET['wp-translations-action'] ) ) {
		do_action( 'wp_translations_' . $_GET['wp-translations-action'], $_GET );
	}

}
add_action( 'admin_init', 'wp_translations_process_actions' );

/**
	* Saves settings
	*
	* @since 1.0
	* @param array $data Project code data
	* @return void
	*/
function wp_translations_save_settings( $data ) {

	if ( ! isset( $data['wp-translations-settings-nonce'] ) || ! wp_verify_nonce( $data['wp-translations-settings-nonce'], 'wp_translations_settings_nonce' ) ) {
		wp_die( esc_html__( 'Trying to cheat or something?', 'wp-translations' ), esc_html__( 'Error', 'wp-translations' ), array( 'response' => 403 ) );
	}
	$options = get_site_option( 'wp_translations_settings' );
	$options['disable_update'] = $data['wp_translations_settings']['disable_update'];
	$options['repo_priority']  = $data['wp_translations_settings']['repo_priority'];

	update_site_option( 'wp_translations_settings', $options );
	wp_redirect( add_query_arg( 'wp-translations-message', 'settings_updated' ) );
}
add_action( 'wp_translations_save_settings', 'wp_translations_save_settings' );

/**
	* Edit Translation
	*
	* @since 1.0
	* @param array $data Project code data
	* @return void
	*/
function wp_translations_save_translation( $data ) {

	if ( ! isset( $data['wp-translations-edit-nonce'] ) || ! wp_verify_nonce( $data['wp-translations-edit-nonce'], 'wp_translations_edit_nonce' ) ) {
		wp_die( esc_html__( 'Trying to cheat or something?', 'wp-translations' ), esc_html__( 'Error', 'wp-translations' ), array( 'response' => 403 ) );
	}

	$options = get_site_option( 'wp_translations_settings' );
	$options['textdomains'][ $data['wp-translations-edit-textdomain'] ] = $data['wp_translations_repo'];

	update_site_option( 'wp_translations_settings', $options );
	wp_redirect( add_query_arg( 'wp-translations-message', 'translation_updated' ) );
}
add_action( 'wp_translations_save_translation', 'wp_translations_save_translation' );
