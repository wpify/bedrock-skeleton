<?php

namespace WpifySkeleton\Managers;

class ControllersManager {
	public function __construct(
		private readonly string $path
	) {
		$this->load_controllers();

	}

	public function load_controllers(  ) {
		$iter = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);
		foreach ($iter as $file) {
			$classname = sprintf("WpifySkeleton\\Controllers\\Views\\%s", str_replace('.php', '', $file->getFilename()));
			if (class_exists($classname)) {
				wpify_skeleton($classname);
			}
		}
	}
}