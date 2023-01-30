<?php

namespace WpifySkeleton\Managers;

use WpifySkeleton\Api\ContactFormApi;

final class ApiManager {
	const PATH = 'wpify-skeleton/v1';

	public function __construct(
		ContactFormApi $contact_form_api,
	) {
	}

	public function get_rest_url( string $route = '' ) {
		if ( empty( $this->rest_url ) ) {
			$this->rest_url = get_rest_url( null, $this::PATH );
		}

		if ( ! empty( $route ) ) {
			return $this->rest_url . '/' . $route;
		}

		return $this->rest_url;
	}
}
