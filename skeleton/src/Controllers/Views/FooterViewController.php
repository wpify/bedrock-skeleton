<?php // phpcs:ignore

namespace WpifySkeleton\Controllers\Views;

use WpifySkeleton\Repositories\SettingsRepository;
use WpifySkeleton\Settings;
use Wpify\Model\PostRepository;

/**
 * SinglePostViewController.
 */
class FooterViewController {
	public function __construct(
		private SettingsRepository $settings_repository,
	) {
	}

	public function get() {
		$id = $this->settings_repository->get_option( Settings::IMPORTANT_PAGES )[ Settings::FOOTER ] ?? '';
		if ( ! $id ) {
			return '';
		}

		return do_blocks( get_post( $id )->post_content );
	}

}
