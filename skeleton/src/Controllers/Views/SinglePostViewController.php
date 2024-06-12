<?php // phpcs:ignore

namespace WpifySkeleton\Controllers\Views;

use WpifySkeleton\Abstracts\AbstractViewController;
use Wpify\Model\PostRepository;

/**
 * SinglePostViewController.
 */
class SinglePostViewController extends AbstractViewController {
	public function __construct(
		private PostRepository $repository,
	) {
		parent::__construct();
	}

	public function views() {
		return array(
			'views/single',
		);
	}

	public function post_types() {
		return array(
			'post',
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
