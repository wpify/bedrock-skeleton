<?php // phpcs:ignore

namespace WpifySkeleton\Models;

use Wpify\Model\Abstracts\AbstractTermModel;
use Wpify\Model\Post;

/**
 * PostModel.
 */
class PostModel extends Post {
	/**
	 * Time to read
	 *
	 * @var int
	 */
	public int $time_to_read;

	/**
	 * Get post permalink.
	 *
	 * @return string
	 */
	public function get_permalink(): string {
		return get_permalink( $this->id );
	}

	/**
	 * Get post image ID.
	 *
	 * @return int
	 */
	public function get_image_id(): int {
		return (int) get_post_thumbnail_id( $this->id );
	}

	/**
	 * Get post category IDS.
	 *
	 * @return array
	 */
	public function get_category_ids(): array {
		return wp_get_post_categories( $this->id, array( 'fields' => 'ids' ) );
	}

	/**
	 * Get post term IDS.
	 *
	 * @return array
	 */
	public function get_term_ids(): array {
		return wp_get_post_terms( $this->id, 'post_tag', array( 'fields' => 'ids' ) );
	}
}
