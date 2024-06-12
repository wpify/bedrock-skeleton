<?php

namespace WpifySkeleton;

use WpifySkeletonDeps\Wpify\CustomFields\CustomFields;

/**
 * Class Settings
 *
 * @package Wpify\Settings
 * @property Plugin $plugin
 */
class Settings {
	const KEY = 'wpify_skeleton_options';
	const FORM_EMAIL = 'form-email';
	const FOOTER = 'footer';
	const SCRIPTS = 'scripts';
	const SCRIPTS_HEADER = 'script_header';
	const SCRIPTS_BODY_START = 'script_body_start';
	const SCRIPTS_BODY_END = 'script_body_end';

	public function __construct( CustomFields $wcf ) {
		$wcf->create_options_page(
			array(
				'parent_slug' => 'options-general.php',
				'page_title'  => __( 'Wpify Skeleton Settings', 'wpify-skeleton' ),
				'menu_title'  => __( 'Wpify Skeleton', 'wpify-skeleton' ),
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
								'title' => _x( 'E-mail for forms', 'settings', 'wpify-skeleton' ),
							),
							array(
								'type'        => 'wysiwyg',
								'id'          => self::FOOTER,
								'title'       => _x( 'Text in the footer', 'settings', 'wpify-skeleton' ),
								'description' => _x( 'You can use placeholder for year <code>[year]</code>.', 'settings', 'wpify-skeleton' ),
							),
							array(
								'type'  => 'group',
								'title' => 'Scripts',
								'id'    => self::SCRIPTS,
								'items' => array(
									array(
										'type'  => 'title',
										'title' => _x( 'Scripts', 'settings', 'wpify-skeleton' ),
										'id'    => 'title',
									),
									array(
										'type'    => 'code',
										'title'   => _x( 'In header', 'settings', 'wpify-skeleton' ),
										'id'      => self::SCRIPTS_HEADER,
										'default' => '',
									),
									array(
										'type'    => 'code',
										'title'   => _x( 'At the body start', 'settings', 'wpify-skeleton' ),
										'id'      => self::SCRIPTS_BODY_START,
										'default' => '',
									),
									array(
										'type'    => 'code',
										'title'   => _x( 'At the body end', 'settings', 'wpify-skeleton' ),
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
}
