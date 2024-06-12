<?php

namespace WpifySkeleton\Managers;

use WpifySkeletonDeps\Wpify\Snippets\CopyrightShortcode;
use WpifySkeletonDeps\Wpify\Snippets\DisableEmojis;
use WpifySkeletonDeps\Wpify\Snippets\RemoveAccentInFilenames;

final class SnippetsManager {
	public function __construct(
		RemoveAccentInFilenames $remove_accent_in_filenames,
		CopyrightShortcode $copyright_shortcode,
		DisableEmojis $disable_emojis,
	) {
	}
}
