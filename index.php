<?php
/**
 * Plugin Name: Simple Social Icons shortcode
 *
 * @author Josh Robbs <josh@joshrobbs.com>
 * @since 2024-02-13
 * @package Simple_Social_Icons
 */

namespace SimpleSocialIcons;

use JWR\JWR_Control_Panel\PHP\JWR_Plugin_Options;
use SimpleSocialIcons\PHP\SSI_Shortcode;

defined( 'ABSPATH' ) || die();

define( 'JWR_SSI_PATH', plugin_dir_path( __FILE__ ) );
define( 'JWR_SSI_SPRITE_URL', \plugin_dir_url( __FILE__ ) . 'images/s_sprite_3.png' );
define( 'JWR_SSI_CHANNELS', array( 'Github', 'LinkedIn', 'Twitter' ) );

require_once JWR_SSI_PATH . 'php/class-ssi-shortcode.php';

/**
 * Add control panel.
 *
 * @return void
 */
function add_control_panel() {
	JWR_Plugin_Options::add_tab( 'Simple Social Icons', 'ssi' );
	JWR_Plugin_Options::start_repeater_field( 'Social Media Links', 'ssi_social_media_links', 'row', 'Add Channel', width: 100 );

	$channels = array();
	foreach ( \JWR_SSI_CHANNELS as $channel ) {
		$channels[ strtolower( $channel ) ] = $channel;
	}

	JWR_Plugin_Options::add_select_field( 'Social Media Channel', 'social_media_channel', $channels, 25 );
	JWR_Plugin_Options::add_url_field( 'URL', 'ssi_url', 75 );
	JWR_Plugin_Options::end_repeater_field();
}

add_action( 'update_jwr_control_panel', __NAMESPACE__ . '\add_control_panel' );

/**
 * Add shortcode.
 *
 * @return string
 */
function ssi_shortcode() {
	return SSI_Shortcode::output();
}

\add_shortcode( 'ssi', __NAMESPACE__ . '\ssi_shortcode' );
