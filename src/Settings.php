<?php

namespace WpifySkeleton;

use Wpify\CustomFields\CustomFields;

/**
 * Class Settings
 *
 * @package Wpify\Settings
 * @property Plugin $plugin
 */
class Settings {
	/**
	 * @var CustomFields
	 */
	public $wcf;

	/**
	 * Option key, and option page slug
	 *
	 * @var string
	 */
	const KEY = 'czechadid_options';

	const FORM_EMAIL = 'form-email';
	const FOOTER = 'footer';
	const SCRIPTS = 'scripts';
	const SCRIPTS_HEADER = 'script_header';
	const SCRIPTS_BODY_START = 'script_body_start';
	const SCRIPTS_BODY_END = 'script_body_end';

	public function __construct( CustomFields $wcf ) {
		$this->wcf = $wcf;

		$this->wcf->create_options_page(
			array(
				'parent_slug' => 'options-general.php',
				'page_title'  => __( 'Czech Ad ID Settings', 'czechadid' ),
				'menu_title'  => __( 'Czech Ad ID', 'czechadid' ),
				'menu_slug'   => self::KEY,
				'capability'  => 'manage_options',
				'items'       => array(
					array(
						'id'    => self::KEY,
						'type'  => 'group',
						'items' => array(
							array(
								'type'  => 'email',
								'id'    => self::FORM_EMAIL,
								'title' => _x( 'E-mail for forms', 'settings', 'czechadid' ),
							),
							array(
								'type'        => 'wysiwyg',
								'id'          => self::FOOTER,
								'title'       => _x( 'Text in the footer', 'settings', 'czechadid' ),
								'description' => _x( 'You can use placeholder for year <code>[year]</code>.', 'settings', 'czechadid' ),
							),
							array(
								'type'  => 'group',
								'title' => 'Scripts',
								'id'    => self::SCRIPTS,
								'items' => array(
									array(
										'type'  => 'title',
										'title' => _x( 'Scripts', 'settings', 'czechadid' ),
										'id'    => 'title',
									),
									array(
										'type'    => 'code',
										'title'   => _x( 'In header', 'settings', 'czechadid' ),
										'id'      => self::SCRIPTS_HEADER,
										'default' => '',
									),
									array(
										'type'    => 'code',
										'title'   => _x( 'At the body start', 'settings', 'czechadid' ),
										'id'      => self::SCRIPTS_BODY_START,
										'default' => '',
									),
									array(
										'type'    => 'code',
										'title'   => _x( 'At the body end', 'settings', 'czechadid' ),
										'id'      => self::SCRIPTS_BODY_END,
										'default' => '',
									),
								),
							),
						),
					),
				),
			)
		);
	}

	public function get_options() {
		return get_option( self::KEY );
	}

	public function get_option( string|array $keys ) {
		$options = get_option( self::KEY );
		$option  = $options;
		$keys    = is_string( $keys ) ? explode( '.', $keys ) : $keys;

		foreach ( $keys as $subkey ) {
			if ( isset( $option[ $subkey ] ) ) {
				$option = $option[ $subkey ];
			} else {
				return null;
			}
		}

		return $option;
	}
}
