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
	const FORM_EMAIL                   = 'form-email';
	const FOOTER                       = 'footer';
	const PROVIDERS                    = 'providers';
	const REVIEWS                      = 'reviews';
	const IMPORTANT_PAGES              = 'important_pages';
	const HEADER                       = 'header';
	const SIDEBAR                      = 'sidebar';
	const PAGE_404                     = 'page_404';
	const ABOUT_US                     = 'about_us';

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
								'title' => __( 'Important pages', 'wpify-skeleton' ),
								'type'  => 'group',
								'id'    => self::IMPORTANT_PAGES,
								'items' => array(
									array(
										'title'     => __( 'Header', 'wpify-skeleton' ),
										'id'        => self::HEADER,
										'type'      => 'post',
										'post_type' => 'wp_block',
									),
									array(
										'title'     => __( 'Footer', 'wpify-skeleton' ),
										'id'        => self::FOOTER,
										'type'      => 'post',
										'post_type' => 'wp_block',
									),
									array(
										'title'     => __( 'Sidebar', 'wpify-skeleton' ),
										'id'        => self::SIDEBAR,
										'type'      => 'post',
										'post_type' => 'wp_block',
									),
									array(
										'title'     => __( 'Providers', 'wpify-skeleton' ),
										'id'        => self::PROVIDERS,
										'type'      => 'post',
										'post_type' => 'page',
									),
									array(
										'title'     => __( 'Reviews', 'wpify-skeleton' ),
										'id'        => self::REVIEWS,
										'type'      => 'post',
										'post_type' => 'page',
									),
									array(
										'title'     => __( 'About us', 'wpify-skeleton' ),
										'id'        => self::ABOUT_US,
										'type'      => 'post',
										'post_type' => 'page',
									),
									array(
										'title'     => __( '404', 'wpify-skeleton' ),
										'id'        => self::PAGE_404,
										'type'      => 'post',
										'post_type' => 'wp_block',
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
