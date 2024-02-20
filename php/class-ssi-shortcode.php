<?php
/**
 * Main shortcode class.
 *
 * @package Simple_Social_Icons
 */

namespace SimpleSocialIcons\PHP;

defined( 'ABSPATH' ) || die();

/**
 * Shortcode class.
 */
class SSI_Shortcode {

	/**
	 * Github URL
	 *
	 * @var string
	 */
	public $github_url;

	/**
	 * LinkedIn URL
	 *
	 * @var string
	 */
	public $linkedin_url;

	/**
	 * Twitter URL
	 *
	 * @var string
	 */
	public $twitter_url;

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->check_social_media_channels();
	}

	/**
	 * Check social media channels.
	 *
	 * @return void
	 */
	private function check_social_media_channels() {
		if ( \have_rows( 'ssi_social_media_links', 'option' ) ) {
			while ( \have_rows( 'ssi_social_media_links', 'option' ) ) {
				\the_row();
				$channel                   = \get_sub_field( 'social_media_channel' );
				$url                       = \get_sub_field( 'ssi_url' );
				$this->{$channel . '_url'} = $url;
			}
		}
	}

	/**
	 * Return link HTML.
	 *
	 * @param string $channel Channel.
	 *
	 * @return string
	 */
	private function return_link_html( $channel ) {
		$lower_channel = \strtolower( $channel );
		if ( ! isset( $this->{$lower_channel . '_url'} ) ) {
			return;
		}
		$channel_url = $this->{$lower_channel . '_url'};
		$sprite_url  = \JWR_SSI_SPRITE_URL;

		$html = <<<HTML
			<a class='ssi-{$lower_channel}' target='_blank' href='{$channel_url}'><img src='{$sprite_url}' alt='Josh on {$channel}' class='ssi-icon'></a>
		HTML;
		return $html;
	}

	/**
	 * Return HTML.
	 *
	 * @return string
	 */
	public static function output() {
		$ssi_shortcode = new self();

		$links = '';

		foreach ( \JWR_SSI_CHANNELS as $channel ) {
			$links .= $ssi_shortcode->return_link_html( $channel );
		}

		$css = <<<CSSx
			<style>
				.ssi {
					display: flex;
					justify-content: center;
				}
				.ssi > a {
					display: inline-block;
					position: relative;
					height: 30px;
					width: 30px;
					overflow: hidden;
					margin: 0 4.5px;
					transition: all ease 0.5s;
				}
				.ssi > a:hover {
					opacity: 0.5;
					transition: all ease 0.5s;
				}
				.ssi > a img {
					position: absolute;
					top: 0;
					left: 0;
				}
				.ssi .ssi-github img {
					clip-path: polygon(66px 0, 100% 0, 100% 100%, 66px 100%);
					left: -66px;
				}
				.ssi .ssi-linkedin img {
					clip-path: polygon(34px 1px, 64px 1px, 64px 31px, 32px 31px);
					left: -34px;
				}
				.ssi .ssi-twitter img {
					clip-path: polygon(1px 1px, 32px 1px, 32px 31px, 1px 31px);
				}
			</style>
		CSSx;

		$html = <<<HTML
			$css
			<div class="ssi">
				$links
			</div>
		HTML;

		return $html;
	}
}
