<?php

namespace WpifySkeleton\Repositories;

use WpifySkeletonDeps\Wpify\Model\PostRepository;
use WpifySkeleton\Models\ContactModel;
use WpifySkeleton\PostTypes\ContactPostType;

/**
 * @method ContactModel create()
 */
class ContactRepository extends PostRepository {
	public function model(): string {
		return ContactModel::class;
	}

	public function post_types(): array {
		return array( ContactPostType::KEY );
	}
}
