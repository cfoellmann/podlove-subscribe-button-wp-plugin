<?php
/**
 * Plugin Name: Podlove Subscribe Button
 * Plugin URI:  http://wordpress.org/extend/plugins/podlove-subscribe-button/
 * Description: Brings the Podlove Subscribe Button to your WordPress installation.
 * Version:     1.0-alpha
 * Author:      Podlove
 * Author URI:  http://podlove.org
 * License:     MIT
 * License URI: license.txt
 * Text Domain: podlove
 */

require('settings/podcasts.php');
// Models
require('model/base.php');
require('model/podcast.php');
// Table
require('settings/podcasts_list_table.php');
// Media Types
require('media_types.php');
// Widget
require('widget.php');

add_action( 'admin_menu', array( 'PodloveSubscribeButton', 'admin_menu') );
add_action( 'admin_init', array( 'PodloveSubscribeButton', 'register_settings') );
add_action( 'admin_init', array( 'PodloveSubscribeButton\Settings\Podcasts', 'process_form' ) );

add_shortcode( 'podlove-subscribe-button', array( 'PodloveSubscribeButton', 'shortcode' ) );

class PodloveSubscribeButton {

	public static function admin_menu() {
		add_options_page(
				'Podlove Subscribe Button Options',
				'Podlove Subscribe Button',
				'manage_options',
				'podlove-subscribe-button',
				array( 'PodloveSubscribeButton\Settings\Podcasts', 'page')
			);
	}

	public static function register_settings() {
		\PodloveSubscribeButton\Model\Podcast::build();		
	}

	public static function shortcode( $args ) {
		if ( ! $args || ! $args['id'] )
			return __('You need create a Button first and provide its ID.', 'podlove');

		if ( ! $button = \PodloveSubscribeButton\Model\Podcast::find_one_by_property('name', $args['id']) )
			return __('Oops. There is no button with the provided ID.', 'podlove');

		$autowidth = ( isset($args['width']) && $args['width'] == 'auto' ? 'on' : '' ); // "on" because this value originates from a checkbox
		$style = ( isset($args['style']) && in_array($args['style'], array('small', 'medium', 'big', 'big-logo')) ? $args['style'] : 'medium' );

		return $button->button( $style, $autowidth );
	}

}