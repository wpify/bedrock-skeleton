<?php
add_action( 'setup_theme', function () {
	if ( ! defined( 'WP_INSTALLING' ) || ! WP_INSTALLING ) {
		switch_theme( 'wpify-skeleton' );
		unlink( __FILE__ );
	};
} );
