<?php // phpcs:ignore

use DI\Definition\Helper\CreateDefinitionHelper;
use Wpify\CustomFields\CustomFields;
use Wpify\Model\Manager;
use Wpify\PluginUtils\PluginUtils;
use Wpify\Templates\TwigTemplates;
use Wpify\Templates\WordPressTemplates;

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
				'extensions' => array(),
				'functions'   => array(
					'bdump' => 'bdump',
					'wp_image' => 'wp_get_attachment_image',
					'__' => '__',
					'wp_image_url' => 'wp_get_attachment_image_url',
				),
			)
		),
	PluginUtils::class        => ( new CreateDefinitionHelper() )
		->constructor( __DIR__ . '/web/app/mu-plugins/wpify-skeleton/wpify-skeleton.php' ),
	Manager::class        => ( new CreateDefinitionHelper() )
		->constructor( array() ),
);
