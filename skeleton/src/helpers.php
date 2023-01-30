<?php

function wpify_skeleton_inline_svg( string $filename, array $attrs = array() ): void {
	if ( ! str_ends_with( $filename, '.svg' ) ) {
		$filename .= '.svg';
	}

	$paths = array(
		wpify_skeleton_root_path( 'assets/images' ),
		wpify_skeleton_root_path( 'assets/sprites' ),
	);

	if ( ! file_exists( $filename ) ) {
		foreach ( $paths as $path ) {
			if ( file_exists( $path . DIRECTORY_SEPARATOR . $filename ) ) {
				$filename = $path . DIRECTORY_SEPARATOR . $filename;
				break;
			}
		}
	}

	if ( file_exists( $filename ) ) {
		$content = preg_replace( '/<\?xml[^>]*>/m', '', file_get_contents( $filename ) );

		foreach ( $attrs as $name => $value ) {
			$content = preg_replace( '/<svg([^>]*)>/m', '<svg$1 ' . $name . '="' . esc_attr( $value ) . '">', $content );
		}

		echo $content;
	}
}

function wpify_skeleton_root_path( $file = '' ) {
	return trailingslashit( realpath( ABSPATH . '../../' ) ) . ltrim( $file, '/' );
}