<?php

namespace WpifySkeleton\Managers;

use WpifySkeleton\Controllers\Views\GlobalViewController;
use WpifySkeleton\Controllers\Views\SinglePageViewController;
use WpifySkeleton\Controllers\Views\SinglePostViewController;

class ControllersManager {
	public function __construct(
		GlobalViewController $global_view_controller,
		SinglePageViewController $single_page_view_controller,
		SinglePostViewController $single_post_view_controller
	) {

	}
}