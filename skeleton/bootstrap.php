<?php
use Tracy\Debugger;
use Wpify\Tracy\Tracy;
use WpifySkeleton\Plugin;
use WpifySkeletonDeps\DI\Container;
use WpifySkeletonDeps\DI\ContainerBuilder;
use function Env\env;

/**
 * @return Plugin
 * @throws Exception
 */
function wpify_skeleton( $class = '' ) {
	static $container;

	if ( empty( $container ) ) {
		$is_production     = ! WP_DEBUG;
		$definition        = require_once __DIR__ . '/config.php';
		$container_builder = new ContainerBuilder();
		$container_builder->addDefinitions( $definition );
		$container = $container_builder->build();
	}

	if ( ! empty( $class ) ) {
		return $container->get( $class );
	}

	return $container;
}

/**
 * Init
 *
 * @return void
 * @throws Exception
 */
function wpify_skeleton_init(): void {
	test_site( Plugin::class );
}

add_action( 'plugins_loaded', 'wpify_skeleton_init', 11 );

add_filter( 'load_script_textdomain_relative_path', function ( $path, $src ) {
	if ( str_contains( $src, '/mu-plugins/wpify-skeleton/' ) ) {
		$path = 'web/app/mu-plugins/wpify-skeleton/' . $path;
	}

	return $path;
}, 10, 2 );

if ( class_exists( 'Tracy\Debugger' ) ) {
	define( 'WPIFY_TRACY_ENABLE', true );
	Debugger::$dumpTheme     = 'dark';
	Debugger::$editor        = 'phpstorm://open?file=%file&line=%line';
	Debugger::$editorMapping = array(
		'/var/www/html' => env( 'DEV_LOCAL_PATH' ),
	);

	new Tracy();
}
