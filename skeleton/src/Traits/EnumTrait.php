<?php

namespace WpifySkeleton\Traits;

trait EnumTrait {
	/**
	 * @return array
	 */
	public static function select(): array {
		return array_map(
			fn( $item ) => array(
				'value' => $item->value,
				'label' => $item->label(),
			),
			self::cases()
		);
	}
}
