<?php

namespace WpifySkeleton\Repositories;

use WpifySkeleton\Settings;

class SettingsRepository {

	/**
	 * @var array
	 */
	public array $options = array();

	/**
	 * @param string $key Key.
	 * @param mixed  $default Default value.
	 * @param bool   $force_reload Force reload options.
	 *
	 * @return mixed
	 */
	public function get_option( string $key = '', mixed $default = null, bool $force_reload = false ): mixed {
		if ( $force_reload || ! $this->options ) {
			$this->get_options( $force_reload );
		}

		if ( isset( $this->options[ $key ] ) ) {
			return $this->options[ $key ];
		}

		return $default ?: false;
	}

	/**
	 * Get all options
	 *
	 * @param bool $force_reload Force reload options.
	 *
	 * @return array
	 */
	public function get_options( bool $force_reload = false ): array {
		if ( $force_reload || ! $this->options ) {
			$this->options = get_option( Settings::KEY, array() );
		}

		return $this->options;
	}
}
