<?php

namespace WpifySkeleton;

use WpifySkeleton\Features\Backend;
use WpifySkeleton\Features\CLI;
use WpifySkeleton\Features\Frontend;
use WpifySkeleton\Managers\ApiManager;
use WpifySkeleton\Managers\BlocksManager;
use WpifySkeleton\Managers\FeaturesManager;
use WpifySkeleton\Managers\PostTypesManager;
use WpifySkeleton\Managers\RepositoryManager;
use WpifySkeleton\Managers\SnippetsManager;
use WpifySkeleton\Managers\TaxonomiesManager;
use WpifySkeletonDeps\Wpify\PluginUtils\PluginUtils;

final class Plugin {
	public function __construct(
		RepositoryManager $repository_manager,
		ApiManager $api_manager,
		BlocksManager $blocks_manager,
		PostTypesManager $post_types_manager,
		TaxonomiesManager $taxonomies_manager,
		SnippetsManager $snippets_manager,
		Backend $backend,
		Frontend $frontend,
		Settings $settings,
		PluginUtils $utils,
		FeaturesManager $features_manager
	) {
		require_once 'helpers.php';
		require_once 'overrides.php';

		load_muplugin_textdomain( 'wpify-skeleton', 'wpify-skeleton/languages' );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			wpify_skeleton_container()->get( CLI::class );
		}
	}
}
