<?php // phpcs:ignore

namespace WpifySkeleton\Models;

use WpifySkeletonDeps\Wpify\Model\Attributes\Meta;
use WpifySkeletonDeps\Wpify\Model\Post;

/**
 * PostModel.
 */
class ContactModel extends Post {
	#[Meta]
	public string $name = '';

	#[Meta]
	public string $company = '';

	#[Meta]
	public string $email = '';

	#[Meta]
	public string $phone = '';

	#[Meta]
	public string $message = '';
}
