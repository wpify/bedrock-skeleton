<?php // phpcs:ignore

namespace WpifySkeleton\Models;

use Wpify\Model\Abstracts\AbstractPostModel;

/**
 * PostModel.
 */
class ContactModel extends AbstractPostModel {
	public string $name = '';
	public string $company = '';
	public string $email = '';
	public string $phone = '';
	public string $message = '';
}
