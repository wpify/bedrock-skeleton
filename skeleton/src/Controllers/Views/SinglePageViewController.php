<?php // phpcs:ignore

namespace WpifySkeleton\Controllers\Views;

use WpifySkeleton\Abstracts\AbstractViewController;
use WpifySkeletonDeps\Wpify\Model\PostRepository;

/**
 * SinglePostViewController.
 */
class SinglePageViewController extends AbstractViewController {
	public function __construct(
		private PostRepository $repository,
	) {
		parent::__construct();
	}

	public function views() {
		return array(
			'views/page',
		);
	}

	public function post_types() {
		return array(
			'page',
		);
	}

	public function data( $args, $slug, $name ) {
		global $post;
		$item = $this->repository->get($this->get_queried_object_id())->to_array();

		return array(
			'content' => apply_filters( 'the_content', $post->post_content ),
			'item'    => $item,
		);
	}
}
