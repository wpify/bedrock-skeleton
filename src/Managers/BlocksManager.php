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

use Wpify\Asset\AssetFactory;
use Wpify\PluginUtils\PluginUtils;

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
}
