<?php

namespace WpifySkeleton\Repositories;

use Wpify\Model\Abstracts\AbstractPostRepository;
use WpifySkeleton\Models\PostModel;
use WpifySkeleton\PostTypes\PostPostType;

class PostRepository extends AbstractPostRepository {
	public function model(): string {
		return PostModel::class;
	}

	static function post_type(): string {
		return PostPostType::KEY;
	}
}
