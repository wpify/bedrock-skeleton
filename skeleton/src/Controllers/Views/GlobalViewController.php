<?php // phpcs:ignore

namespace WpifySkeleton\Controllers\Views;

use WpifySkeleton\Repositories\SettingsRepository;
use WpifySkeleton\Settings;
use Wpify\Model\PostRepository;
use WpifyMultilingual\Repositories\SitesRepository;

/**
 * SinglePostViewController.
 */
class GlobalViewController {
	public function __construct(
		private SettingsRepository $settings_repository,
	) {
		add_filter( 'wpify_templates_template_args', array( $this, 'add_twig_data' ), 10, 5 );
	}

	public function add_twig_data( $data ) {
		$data['global'] = array(
			'site_url' => home_url(),
			'wp'       => true,
		);
		if ( function_exists( 'wpify_multilingual' ) ) {
			$data['translations'] = apply_filters( 'wpify_multilingual_get_localized_versions', array() );

		}

		return $data;
	}
}
