<?php

namespace WpifySkeleton\Controllers;

use Wpify\Model\MenuItem;
use Wpify\Model\MenuRepository;
use WpifySkeleton\Frontend;
use WpifySkeleton\Settings;

class FooterController {
	public function __construct( private MenuRepository $menu_repository, private Settings $settings ) {
	}

	/**
	 * Returns the footer menu.
	 *
	 * @return MenuItem[]
	 */
	public function menu(): array {
		$menu = $this->menu_repository->get( Frontend::FOOTER_MENU );

		return $menu->items;
	}

	public function scripts_body_end(): string {
		return $this->settings->get_option( Settings::SCRIPTS_BODY_END ) ?? '';
	}

	public function copyright(): string {
		return do_shortcode( $this->settings->get_option( Settings::FOOTER ) ?? '' );
	}
}