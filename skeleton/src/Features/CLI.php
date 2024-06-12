<?php // phpcs:ignore

namespace WpifySkeleton\Features;

use WP_CLI;

/**
 * Class CLI
 *
 * @property Plugin $plugin
 */
class CLI {
	/**
	 * Construct.
	 */
	public function __construct() {
		WP_CLI::add_command( 'wpify-skeleton test', array( $this, 'run_test' ) );
	}

	public function run_test(): void {
		WP_CLI::success( 'Test' );
	}
}
