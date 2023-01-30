<?php

namespace WpifySkeleton;

use WpifySkeleton\Managers\ApiManager;
use WpifySkeleton\Managers\BlocksManager;
use WpifySkeleton\Managers\PostTypesManager;
use WpifySkeleton\Managers\SnippetsManager;
use WpifySkeleton\Managers\TaxonomiesManager;
use Wpify\PluginUtils\PluginUtils;

final class Plugin {
	public function __construct(
		ApiManager $api_manager,
		BlocksManager $blocks_manager,
		PostTypesManager $post_types_manager,
		TaxonomiesManager $taxonomies_manager,
		SnippetsManager $snippets_manager,
		Backend $admin,
		Frontend $frontend,
		Settings $settings,
		PluginUtils $utils,
	) {
		require_once 'helpers.php';
		require_once 'overrides.php';

		load_muplugin_textdomain( 'wpify-skeleton', 'wpify-skeleton/languages' );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			wpify_skeleton_container()->get( CLI::class );
		}
	}
}
