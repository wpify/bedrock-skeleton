<?php

namespace WpifySkeleton\Managers;

use WpifySkeleton\PostTypes\ContactPostType;
use WpifySkeleton\PostTypes\PagePostType;
use WpifySkeleton\PostTypes\PostPostType;

final class PostTypesManager {
	public function __construct(
		PagePostType $page_post_type,
		PostPostType $post_post_type,
		ContactPostType $article_post_type,
	) {
	}
}
