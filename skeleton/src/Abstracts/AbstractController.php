<?php // phpcs:ignore

namespace WpifySkeleton\Abstracts;

use WpifySkeletonDeps\Wpify\Model\Interfaces\ModelInterface;
use WpifySkeletonDeps\Wpify\Model\Interfaces\RepositoryInterface;

/**
 * AbstractController.
 */
abstract class AbstractController {
	/**
	 * Repository.
	 *
	 * @return RepositoryInterface
	 */
	abstract public function repository(): RepositoryInterface;

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
	 * Get items.
	 *
	 * @param array $args   Args.
	 * @param array $fields Fields.
	 *
	 * @return array
	 */
	public function get_items( array $args = array(), array $fields = array() ): array {
		$args  = wp_parse_args(
			$args,
			array(
				'post_status' => 'publish',
			)
		);
		$items = $this->repository()->find( $args );

		return $this->collection_to_array( $items, $fields );
	}

	/**
	 * Get item.
	 *
	 * @param int   $id     ID.
	 * @param array $fields Fields.
	 *
	 * @return array
	 */
	public function get_item( int $id, array $fields = array() ): array {
		$item = $this->repository()->get( $id );

		return $this->decorate( $item->to_array( $fields ), $item );
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
}
