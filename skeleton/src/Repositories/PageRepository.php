<?php

namespace WpifySkeleton\Repositories;

use Wpify\Model\Abstracts\AbstractPostRepository;
use WpifySkeleton\Models\PageModel;
use WpifySkeleton\PostTypes\PagePostType;

class PageRepository extends AbstractPostRepository {
	public function model(): string {
		return PageModel::class;
	}

	static function post_type(): string {
		return PagePostType::KEY;
	}
}
