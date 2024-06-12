<?php

namespace WpifySkeleton\Enums;

use WpifySkeleton\Traits\EnumTrait;

enum OrderStatusEnum: string {
	use EnumTrait;

	case ACCEPTED        = 'accepted';
	case DISPATCHED      = 'dispatched';
	case APPROVED        = 'approved';
	case PICKED_UP       = 'picked-up';
	case READY_TO_PICKUP = 'ready-to-pickup';

	/**
	 * Get label
	 *
	 * @return string|null
	 */
	public function label(): ?string {
		return match ( $this ) {
			self::ACCEPTED => __( 'Accepted', 'events-manager' ),
			self::DISPATCHED => __( 'Dispatched', 'events-manager' ),
			self::APPROVED => __( 'Approved', 'events-manager' ),
			self::PICKED_UP => __( 'Picked up', 'events-manager' ),
			self::READY_TO_PICKUP => __( 'Ready to pick up', 'events-manager' ),
		};
	}
}
