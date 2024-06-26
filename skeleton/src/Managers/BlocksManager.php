<?php

namespace WpifySkeleton\Managers;

use WpifySkeleton\Blocks\AboutBlock;
use WpifySkeleton\Blocks\ArticlesBlock;
use WpifySkeleton\Blocks\ContactBlock;
use WpifySkeleton\Blocks\EditorBlock;
use WpifySkeleton\Blocks\HeroBlock;
use WpifySkeleton\Blocks\IconBoxesBlock;
use WpifySkeleton\Blocks\NumbersBlock;
use WpifySkeleton\Blocks\SeparatorBlock;
use WpifySkeleton\Blocks\WorkflowBlock;

use WpifySkeletonDeps\Wpify\Asset\AssetFactory;
use WpifySkeletonDeps\Wpify\PluginUtils\PluginUtils;

final class BlocksManager {
	private $utils;
	private $asset_factory;

	public function __construct(
		AssetFactory $asset_factory,
		ContactBlock $contact_block,
		PluginUtils $utils,
	) {
		$this->utils         = $utils;
		$this->asset_factory = $asset_factory;

		add_action( 'after_setup_theme', array( $this, 'editor_styles' ) );
		add_filter( 'block_categories_all', array( $this, 'block_categories' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'show_reusable_blocks_admin_menu' ) );

		$this->asset_factory->admin_wp_script( $this->utils->get_plugin_path( 'build/block-editor.js' ) );
	}

	public function editor_styles() {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'editor-style.css' );
	}

	public function block_categories( $categories, $post ) {
		$categories[] = array(
			'slug'  => 'wpify-skeleton',
			'title' => __( 'Wpify Skeleton', 'wpify-skeleton' ),
			'icon'  => 'wordpress',
		);

		return $categories;
	}

	public function show_reusable_blocks_admin_menu() {
		add_menu_page( 'Reusable Blocks', 'Reusable Blocks', 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 22 );
	}
}
