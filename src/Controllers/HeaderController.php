<?php

namespace WpifySkeleton\Controllers;

use Wpify\Model\MenuItem;
use Wpify\Model\MenuRepository;
use WpifySkeleton\Frontend;

class HeaderController {
	public function __construct( private MenuRepository $menu_repository ) {
	}

	/**
	 * Returns the primary menu.
	 *
	 * @return MenuItem[]
	 */
	public function menu(): array {
		$menu = $this->menu_repository->get( Frontend::PRIMARY_MENU );

		return $menu->items;
	}
}