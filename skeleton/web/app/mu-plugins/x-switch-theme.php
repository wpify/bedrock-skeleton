<?php
add_action( 'muplugins_loaded', function () {
	if ( ! defined( 'WP_INSTALLING' ) || ! WP_INSTALLING ) {
		switch_theme( 'wpify-skeleton' );
		unlink( __FILE__ );
	};
} );
