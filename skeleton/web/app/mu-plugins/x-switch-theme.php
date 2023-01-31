<?php
add_action( 'init', function () {
	switch_theme( 'wpify-skeleton' );
	unlink( __FILE__ );
} );
