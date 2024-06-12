<?php

namespace WpifySkeleton\Features;

use WpifySkeletonDeps\DI\DependencyException;
use WpifySkeletonDeps\DI\NotFoundException;
use WpifySkeletonDeps\Twig\Extension\AbstractExtension;
use WpifySkeletonDeps\Twig\TwigFunction;
use WpifySkeletonDeps\Twig\TwigTest;


class TwigExtensions extends AbstractExtension {
	/**
	 * Get the custom functions.
	 *
	 * @return array
	 */
	public function getFunctions(): array {
		return array(
			new TwigFunction(
				'render_block',
				array( $this, 'render_block' ),
				array(
					'is_safe' => array(
						'html',
					),
				)
			),
			new TwigFunction(
				'wp_image',
				array( $this, 'render_image' ),
				array(
					'is_safe' => array(
						'html',
					),
				)
			),
			new TwigFunction(
				'do_shortcode',
				'do_shortcode',
				array(
					'is_safe' => array(
						'html',
					),
				)
			),
			new TwigFunction(
				'inline_svg',
				array( $this, 'inline_svg' ),
				array(
					'is_safe' => array(
						'html',
					),
				)
			),
			new TwigFunction(
				'strip_links',
				array( $this, 'strip_links' ),
				array(
					'is_safe' => array(
						'html',
					),
				)
			),
			new TwigFunction(
				'set_global',
				array( $this, 'set_global' ),
				array(
					'is_safe' => array(
						'html',
					),
				)
			),
			new TwigFunction(
				'wc_custom_products_loop',
				array( $this, 'wc_custom_products_loop' ),
				array(
					'is_safe' => array(
						'html',
					),
				)
			),
		);
	}

	public function getTests() {
		return array(
			new TwigTest(
				'numeric',
				function ( $value ) {
					return is_numeric( $value );
				}
			),
		);
	}

	/**
	 * @param $id
	 *
	 * @return string
	 * @throws DependencyException DependencyException.
	 * @throws NotFoundException NotFoundException.
	 */
	public function render_block( $id ): string {
		return hyponamiru_container()->get( BlockController::class )->render_block( $id );
	}

	public function render_image( $id, $size = 'full', $crop = true, $attr = array() ) {
		if ( ! $size ) {
			$size = 'full';
		}
		$attr['retina'] = true;
		if ( function_exists( 'bis_get_attachment_image' ) ) {
			return bis_get_attachment_image( $id, $size, $crop, $attr );
		}

		return wp_get_attachment_image( $id, $size, null, $attr );
	}

	public function inline_svg( string $filename, array $attrs = array() ) {
		if ( ! str_ends_with( $filename, '.svg' ) ) {
			$filename .= '.svg';
		}

		$paths = array(
			hyponamiru_root_path( 'assets/images' ),
			hyponamiru_root_path( 'assets/sprites' ),
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

			return $content;
		}

		return '';
	}

	public function strip_links( $content ): string {
		return preg_replace( '#<a.*?>(.*?)</a>#i', '\1', $content );
	}

	public function set_global( $name, $value ) {
		$GLOBALS[ $name ] = $value;
	}

	public function wc_custom_products_loop( array $product_ids ) {
		$ids = array();
		foreach ( $product_ids as $product_id ) {
			if ( is_numeric( $product_id ) ) {
				$ids[] = $product_id;
			}
			if ( is_array( $product_id ) && isset( $product_id['id'] ) ) {
				$ids[] = $product_id['id'];
			}
		}

		$product_query = new \WP_Query(
			array(
				'post_type' => 'any',
				'post__in'  => $ids,
			)
		);


		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

		wc_set_loop_prop( 'current_page', $paged );
		wc_set_loop_prop( 'is_paginated', wc_string_to_bool( true ) );
		wc_set_loop_prop( 'page_template', get_page_template_slug() );
		wc_set_loop_prop( 'per_page', 10 );
		wc_set_loop_prop( 'total', $product_query->found_posts );
		wc_set_loop_prop( 'total_pages', $product_query->max_num_pages );

		woocommerce_product_loop_start();

		if (wc_get_loop_prop( 'total' )) {
			while ( $product_query->have_posts() ) {
				$product_query->the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}
		}


		woocommerce_product_loop_end();
	}
}
