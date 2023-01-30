<?php

namespace WpifySkeleton\Repositories;

use Wpify\Model\Abstracts\AbstractPostRepository;
use WpifySkeleton\Models\ContactModel;
use WpifySkeleton\PostTypes\ContactPostType;

class ContactRepository extends AbstractPostRepository {
	public function model(): string {
		return ContactModel::class;
	}

	static function post_type(): string {
		return ContactPostType::KEY;
	}
}
