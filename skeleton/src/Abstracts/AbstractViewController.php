<?php // phpcs:ignore

namespace WpifySkeleton\Abstracts;

use WpifySkeletonDeps\Wpify\Model\Interfaces\ModelInterface;
use WpifySkeletonDeps\Wpify\Model\Interfaces\RepositoryInterface;

/**
 * AbstractController.
 */
abstract class AbstractViewController {

	public function __construct() {
		add_filter( 'wpify_templates_template_args', array( $this, 'add_twig_data' ), 10, 5 );
	}

	public function add_twig_data( $args, $slug, $name ) {
		if ( ! empty( $this->views() ) ) {
			$matches = false;
			foreach ( $this->views() as $view ) {
				if ( $this->view_matches( $slug, $name, $view ) ) {
					$matches = true;
					break;
				}
			}
			if ( ! $matches ) {
				return $args;
			}
		}

		if ( ! empty( $this->post_types() ) ) {
			$matches = false;
			foreach ( $this->post_types() as $post_type ) {
				if ( $this->is_post_type( $post_type ) ) {
					$matches = true;
					break;
				}
			}
			if ( ! $matches ) {
				return $args;
			}
		}

		return wp_parse_args(
			$this->data( $args, $slug, $name ),
			$args,
		);
	}


	/**
	 * Collection to array.
	 *
	 * @param array $collection Collection.
	 * @param array $fields     Fields.
	 *
	 * @return array
	 */
	public function collection_to_array( array $collection, array $fields = array() ): array {
		return array_map( fn( $item ) => $this->decorate( $item->to_array( $fields ), $item ), $collection );
	}

	/**
	 * Decorate.
	 *
	 * @param array          $data Data.
	 * @param ModelInterface $item Item.
	 *
	 * @return array
	 */
	public function decorate( array $data, ModelInterface $item ): array {
		return $data;
	}

	public function view_matches( $slug, $name, $view ) {
		if ( $name ) {
			$slug = sprintf( '%s-%s', $slug, $name );
		}
		$replaces = array(
			'.twig' => '',
		);
		$slug     = ltrim( str_replace( array_keys( $replaces ), array_values( $replaces ), $slug ), '/' );

		return $view === $slug;
	}

	public function is_post_type( string $post_type ): bool {
		$object = get_queried_object();

		return isset( $object->post_type ) && $post_type === $object->post_type;
	}

	public function get_queried_object_id() {
		return get_queried_object_id();
	}

	public function data( $args, $slug, $name ) {
		return [];
	}

	public function views() {
		return [];
	}

	public function post_types() {
		return array();
	}

}
