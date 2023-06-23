<?php

namespace WpifySkeleton\Managers;

use Wpify\Model\Manager;

class RepositoryManager {
	public function __construct(
		Manager $manager
	) {
		foreach ( $manager->get_repositories() as $repository ) {
			wpify_skeleton_container()->set( $repository::class, $repository );
		}
	}
}