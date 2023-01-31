<?php

namespace WpifySkeleton;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

/**
 * Installer of the new project based on Bedrock with WPify packages.
 */
class Installer {
	/**
	 * List all files in given directory.
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	private static function recursive_files( string $path ): array {
		$files = array();

		if ( is_dir( $path ) ) {
			if ( $handle = opendir( $path ) ) {
				while ( ( $name = readdir( $handle ) ) !== false ) {
					if ( ! in_array( $name, array( '..', '.' ) ) ) {
						if ( ! is_dir( $path . "/" . $name ) ) {
							$files[] = $path . '/' . $name;
						} else {
							array_push( $files, ...self::recursive_files( $path . "/" . $name ) );
						}
					}
				}

				closedir( $handle );
			}
		}

		sort( $files );

		return $files;
	}

	/**
	 * List all files in given directory.
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	private static function list_files( string $path ): array {
		$files = self::recursive_files( $path );

		return array_map( function ( $file_path ) use ( $path ) {
			return substr( $file_path, strlen( $path ) + 1 );
		}, $files );
	}

	/**
	 * Delete recursively folder.
	 *
	 * @param string $path
	 */
	private static function delete( string $path ): void {
		if ( is_dir( $path ) ) {
			$files = array_diff( scandir( $path ), array( '.', '..' ) );

			foreach ( $files as $file ) {
				( is_dir( "$path/$file" ) ) ? self::delete( "$path/$file" ) : unlink( "$path/$file" );
			}

			rmdir( $path );
		} else {
			unlink( $path );
		}
	}

	/**
	 * Get the case of the given name.
	 *
	 * @param string $name
	 * @param string $case
	 *
	 * @return string
	 */
	private static function get_case( string $name, string $case ) {
		// remove accents
		$name = str_replace( '\'', '', iconv( 'UTF-8', 'ASCII//TRANSLIT', $name ) );

		// add space before capital letters
		$name = preg_replace( '/(?<!\ )[A-Z]/', ' $0', $name );

		// remove all non-alphanumeric characters
		$name = trim( preg_replace( '/[^a-zA-Z0-9]/', ' ', $name ) );

		// make all lowercase
		$name = strtolower( $name );

		// split into words
		$name = preg_split( '/\s+/', $name );

		switch ( $case ) {
			case 'camel':
			{
				return lcfirst( join( '', array_map( fn( $word ) => ucfirst( $word ), $name ) ) );
			}
			case 'pascal':
			{
				return join( '', array_map( fn( $word ) => ucfirst( $word ), $name ) );
			}
			case 'snake':
			{
				return join( '_', $name );
			}
			case 'kebab':
			{
				return join( '-', $name );
			}
			case 'constant':
			{
				return join( '_', array_map( 'strtoupper', $name ) );
			}
			case 'sentence':
			{
				return join( ' ', array_map( 'ucfirst', $name ) );
			}
			default:
			{
				return $name;
			}
		}
	}

	/**
	 * Replace all cases of the given name.
	 *
	 * @param string $search
	 * @param string $replace
	 * @param string $subject
	 *
	 * @return string
	 */
	private static function replace_cases( string $search, string $replace, string $subject ) {
		$cases = array( 'camel', 'pascal', 'snake', 'kebab', 'constant', 'sentence' );

		foreach ( $cases as $case ) {
			$subject = str_replace( self::get_case( $search, $case ), self::get_case( $replace, $case ), $subject );
		}

		return $subject;
	}

	/**
	 * Generate random password.
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	private static function generate_password( int $length = 64 ) {
		$chars    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
		$password = '';

		for ( $i = 0; $i < $length; $i ++ ) {
			$password .= substr( $chars, rand( 0, strlen( $chars ) - 1 ), 1 );
		}

		return $password;
	}

	/**
	 * Modify .env file for the new project.
	 *
	 * @param string $content
	 * @param string $project_name
	 *
	 * @return string
	 */
	private static function dotenv( string $content, string $project_name ) {
		$lines = preg_split( '/\n/', $content );

		foreach ( $lines as $index => $line ) {
			$line = trim( $line );

			if ( str_starts_with( $line, '# DB_HOST=' ) ) {
				$line = trim( trim( $line, '# ' ) );
			}

			if ( str_starts_with( $line, '#' ) || empty( $line ) || ! str_contains( $line, '=' ) ) {
				continue;
			}

			[ $variable, $value ] = preg_split( '/\s*=\s*/', $line, 2 );
			$variable = trim( $variable );
			$value    = trim( $value );
			$quote    = '';

			if ( str_starts_with( $value, '\'' ) && str_ends_with( $value, '\'' ) ) {
				$quote = '\'';
			} elseif ( str_starts_with( $value, '"' ) && str_ends_with( $value, '"' ) ) {
				$quote = '"';
			} elseif ( str_starts_with( $value, '`' ) && str_ends_with( $value, '`' ) ) {
				$quote = '"';
			}

			$value = trim( $value, $quote );

			if ( in_array( $variable, array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST' ) ) ) {
				$value = 'db';
			} elseif ( 'WP_HOME' === $variable ) {
				$value = 'https://www.' . $project_name . '.test';
			} elseif ( in_array( $variable, array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ) ) ) {
				$value = self::generate_password();
			}

			$lines[ $index ] = $variable . '=' . $quote . $value . $quote;
		}

		return implode( "\n", $lines );
	}

	/**
	 * Modify package.json file for the new project.
	 *
	 * @param string $content
	 * @param string $project_name
	 *
	 * @return string
	 */
	private static function composerjson( string $content, string $project_name ) {
		$composer = json_decode( $content, true );

		$composer['name']           = 'wpify/' . self::get_case( $project_name, 'kebab' );
		$composer['description']    = self::get_case( $project_name, 'sentence' );
		$composer['homepage']       = 'https://wpify.io';
		$composer['keywords']       = array();
		$composer['authors']        = array(
			array(
				'name'     => 'WPify',
				'email'    => 'info@wpify.io',
				'homepage' => 'https://wpify.io',
			),
		);
		$composer['require']['php'] = '>=8.0';

		$composer['config']['allow-plugins']['mnsami/composer-custom-directory-installer']     = true;
		$composer['config']['allow-plugins']['dealerdirect/phpcodesniffer-composer-installer'] = true;

		$composer['extra']['installer-paths']['web/app/vendor/{$vendor}/{$name}'] = array( 'wpify/custom-fields' );

		$composer['scripts']['make-pot']  = array(
			'wp i18n make-pot . web/app/mu-plugins/testovaci-projekt/languages/testovaci-projekt.pot --include="src,web/app/mu-plugins/testovaci-projekt,web/app/themes/testovaci-projekt" --domain="testovaci-projekt"',
		);
		$composer['scripts']['make-json'] = array(
			'rm -rf web/app/mu-plugins/testovaci-projekt/languages/json',
			'wp i18n make-json web/app/mu-plugins/testovaci-projekt/languages web/app/mu-plugins/testovaci-projekt/languages/json --no-purge --pretty-print',
		);

		$composer['autoload']['psr4'][ self::get_case( $project_name, 'pascal' ) . '\\' ] = 'src/';

		unset( $composer['support'] );
		unset( $composer['scripts']['test'] );
		unset( $composer['scripts']['post-root-package-install'] );

		return json_encode( $composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}

	/**
	 * Modify package.json file for the new project.
	 *
	 * @param string $content
	 * @param string $project_name
	 *
	 * @return string
	 */
	private static function packagejson( string $content, string $project_name ) {
		$package = json_decode( $content, true );

		unset( $package['dependencies'] );
		unset( $package['devDependencies'] );

		return json_encode( $package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}

	/**
	 * Run command in console.
	 *
	 * @param array $command
	 * @param string|null $cwd
	 * @param ConsoleOutput $output
	 *
	 * @return void
	 */
	private static function console( array $command, ?string $cwd, ConsoleOutput $output ) {
		$process = new Process( $command, $cwd );

		$process->setTty( true );
		$process->mustRun( function ( $type, $buffer ) use ( $output ) {
			$output->write( $buffer );
		} );
	}

	/**
	 * Create new project.
	 */
	public static function post_create_project() {
		$output = new ConsoleOutput();

		$output->writeln( "\n<info> ➤ Initializing</info>\n" );

		$root_dir      = dirname( __DIR__ );
		$skeleton_dir  = $root_dir . '/skeleton';
		$bedrock_dir   = $root_dir . '/bedrock';
		$project_name  = 'testovaci-projekt';
		$skeleton_name = 'wpify-skeleton';
		$source_files  = self::list_files( $skeleton_dir );
		$transfers     = array(
			$bedrock_dir . '/.env.example' => $bedrock_dir . '/.env',
		);

		foreach ( $source_files as $source_file ) {
			$transfers[ $skeleton_dir . '/' . $source_file ] = $bedrock_dir . '/' . self::replace_cases( $skeleton_name, $project_name, $source_file );
		}

		$output->writeln( "\n<info> ➤ Copying files</info>\n" );

		foreach ( $transfers as $source => $destination ) {
			$destination_dir = dirname( $destination );

			if ( ! is_dir( $destination_dir ) ) {
				mkdir( $destination_dir, 0777, true );
			}

			$content = file_get_contents( $source );
			$content = self::replace_cases( $skeleton_name, $project_name, $content );

			if ( 'composer.json' === basename( $destination ) ) {
				$content = file_get_contents( $destination );
				$content = self::composerjson( $content, $project_name );
			}

			if ( '.env' === basename( $destination ) ) {
				$content = self::dotenv( $content, $project_name );
			}

			if ( 'package.json' === basename( $destination ) ) {
				$content = self::packagejson( $content, $project_name );
			}

			file_put_contents( $destination, $content );
		}

		$output->writeln( "\n<info> ➤ Installing composer dependencies</info>\n" );

		self::console(
			array( 'composer', 'remove', 'wpackagist-theme/twentytwentythree', '--ignore-platform-reqs' ),
			$bedrock_dir,
			$output
		);

		$source_composerjson = json_decode( file_get_contents( $skeleton_dir . '/composer.json' ), true );
		$composer_deps       = array_keys( $source_composerjson['require'] );

		self::console(
			array( 'composer', 'require', ...$composer_deps, '--ignore-platform-reqs' ),
			$bedrock_dir,
			$output
		);

		$composer_deps_dev = array_keys( $source_composerjson['require-dev'] );

		self::console(
			array( 'composer', 'require', ...$composer_deps_dev, '--ignore-platform-reqs', '--dev' ),
			$bedrock_dir,
			$output
		);

		$output->writeln( "\n<info> ➤ Installing npm dependencies</info>\n" );

		$source_packagejson = json_decode( file_get_contents( $skeleton_dir . '/package.json' ), true );
		$npm_deps           = array_keys( $source_packagejson['dependencies'] );

		self::console(
			array( 'npm', 'install', ...$npm_deps ),
			$bedrock_dir,
			$output
		);

		$npm_deps_dev = array_keys( $source_packagejson['devDependencies'] );

		self::console(
			array( 'npm', 'install', '--save-dev', ...$npm_deps_dev ),
			$bedrock_dir,
			$output
		);

		$output->writeln( "\n<info> ➤ Building assets</info>\n" );

		self::console(
			array( 'npm', 'run', 'build' ),
			$bedrock_dir,
			$output
		);

		$output->writeln( "\n<info> ➤ Cleanup the files</info>\n" );

		/*
		self::delete( $root_dir . '/installer' );
		self::delete( $root_dir . '/skeleton' );
		self::delete( $root_dir . '/vendor' );
		self::delete( $root_dir . '/.gitignore' );
		self::delete( $root_dir . '/composer.json' );
		self::delete( $root_dir . '/composer.json' );
		*/
	}
}
