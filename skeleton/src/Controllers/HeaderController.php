<?php

namespace WpifySkeleton\Controllers;

use Wpify\Model\Exceptions\RepositoryNotFoundException;
use Wpify\Model\Exceptions\RepositoryNotInitialized;
use Wpify\Model\MenuItem;
use Wpify\Model\MenuRepository;
use WpifySkeleton\Frontend;
use WpifySkeleton\Settings;

class HeaderController {
	public function __construct( private MenuRepository $menu_repository, private Settings $settings ) {
	}

	/**
	 * Returns the primary menu.
	 *
	 * @return MenuItem[]
	 * @throws RepositoryNotFoundException
	 * @throws RepositoryNotInitialized
	 */
	public function menu(): array {
		$menu = $this->menu_repository->get( Frontend::PRIMARY_MENU );

		return $menu->children;
	}

	public function scripts_header(): string {
		return $this->settings->get_option( Settings::SCRIPTS_HEADER ) ?? '';
	}

	public function scripts_body_start(): string {
		return $this->settings->get_option( Settings::SCRIPTS_BODY_START )  ?? '';
	}
}
