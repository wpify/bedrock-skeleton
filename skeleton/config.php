<?php // phpcs:ignore

use WpifySkeleton\Managers\ControllersManager;
use WpifySkeletonDeps\DI\Definition\Helper\CreateDefinitionHelper;
use WpifySkeletonDeps\Wpify\CustomFields\CustomFields;
use WpifySkeletonDeps\Wpify\Model\Manager;
use WpifySkeletonDeps\Wpify\PluginUtils\PluginUtils;
use WpifySkeletonDeps\Wpify\Templates\TwigTemplates;
use WpifySkeletonDeps\Wpify\Templates\WordPressTemplates;
use WpifySkeleton\Features\TwigExtensions;

return array(
	CustomFields::class       => ( new CreateDefinitionHelper() )
		->constructor( content_url( 'vendor/wpify/custom-fields' ) ),
	WordPressTemplates::class => ( new CreateDefinitionHelper() ),
	TwigTemplates::class      => ( new CreateDefinitionHelper() )
		->constructor(
			array( get_template_directory() . '/templates' ),
			array(
				'integrate'  => true,
				'debug'      => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'extensions' => array(
					WpifySkeletonDeps\DI\create( TwigExtensions::class ),
				),
				'functions'   => array(
					'bdump' => 'bdump',
					'wp_image' => 'wp_get_attachment_image',
					'__' => '__',
					'wp_image_url' => 'wp_get_attachment_image_url',
				),
			)
		),
	PluginUtils::class        => ( new CreateDefinitionHelper() )
		->constructor( __DIR__ . '/bootstrap.php' ),
	Manager::class        => ( new CreateDefinitionHelper() )
		->constructor( array() ),
	ControllersManager::class => ( new CreateDefinitionHelper() )
		->constructor( __DIR__.'/src/Controllers/Views' ),
);
