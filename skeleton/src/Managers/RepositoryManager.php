<?php

namespace WpifySkeleton\Managers;

use WpifySkeletonDeps\Wpify\Model\Manager;
use WpifySkeletonDeps\DI\Container;
use WpifySkeleton\Repositories\ContactRepository;

class RepositoryManager {
	public function __construct(
		Container $container,
		Manager $manager,
		ContactRepository $contact_repository,
	) {
		foreach ( $manager->get_repositories() as $repository ) {
			$container->set( $repository::class, $repository );
		}

		$manager->register_repository( $contact_repository );
	}
}
