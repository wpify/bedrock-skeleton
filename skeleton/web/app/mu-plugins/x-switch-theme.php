<?php
add_action( 'setup_theme', function () {
	switch_theme( 'wpify-skeleton' );
	unlink( __FILE__ );
} );
