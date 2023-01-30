<?php // phpcs:ignore

namespace WpifySkeleton\Models;

use Wpify\Model\Post;

/**
 * PageModel.
 */
class PageModel extends Post {
	/**
	 * Get page permalink.
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
}
