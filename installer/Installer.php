<?php

namespace WpifySkeleton;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;
use Wpify\Helpers\Filesystem;
use Wpify\Helpers\Strings;

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

/**
 * Installer of the new project based on Bedrock with WPify packages.
 */
class Installer {
	/**
	 * Modify .env file for the new project.
	 *
	 * @param string $content
	 * @param string $project_name
	 *
	 * @return string
	 */
	private static function dotenv( string $content, string $project_name ) {
		$preprocess_line = function ( $line ) {
			// replace commented DB_HOST with uncommented
			if ( str_starts_with( $line, '# DB_HOST=' ) ) {
				$line = trim( trim( $line, '# ' ) );
			}

			return $line;
		};

		$set_value = function ( $name, $value ) use ( $project_name ) {
			if ( in_array( $name, array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST' ) ) ) {
				$value = 'db';
			} elseif ( 'WP_HOME' === $name ) {
				$value = 'https://www.' . $project_name . '.test';
			} elseif ( in_array( $name, array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ) ) ) {
				$value = Strings::generate_password();
			}

			return $value;
		};

		return Strings::modify_dotenv( $content, $set_value, $preprocess_line );
	}

	/**
	 * Modify composer.json file for the new project.
	 *
	 * @param string $content
	 * @param string $project_name
	 *
	 * @return string
	 */
	private static function composerjson( string $content, string $project_name ) {
		$composer = json_decode( $content, true );

		$composer['name']           = 'wpify/' . Strings::get_case( $project_name, 'kebab' );
		$composer['description']    = Strings::get_case( $project_name, 'sentence' );
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

		$composer['autoload']['psr-4'][ Strings::get_case( $project_name, 'pascal' ) . '\\' ] = 'src/';

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
		$process->setTimeout( 600 );

		$process->setTty( true );
		$process->mustRun( function ( $type, $buffer ) use ( $output ) {
			$output->write( $buffer );
		} );
	}

	/**
	 * Create new project.
	 */
	public static function post_create_project() {
		$output       = new ConsoleOutput();
		$root_dir     = dirname( __DIR__ );
		$skeleton_dir = $root_dir . '/skeleton';
		$bedrock_dir  = $root_dir . '/bedrock';

		$output->writeln( "\n<info> ➤ Initializing</info>\n" );

		Filesystem::delete( $bedrock_dir );
		self::console( array( 'composer', 'create-project', 'roots/bedrock', 'bedrock' ), $root_dir, $output );

		$project_name  = basename( $root_dir );
		$skeleton_name = 'wpify-skeleton';

		if ( $project_name === $skeleton_name ) {
			$project_name = 'generated-project';
		}

		$source_files = Filesystem::list_files( $skeleton_dir );
		$transfers    = array(
			$bedrock_dir . '/.env.example' => $bedrock_dir . '/.env',
		);

		foreach ( $source_files as $source_file ) {
			$transfers[ $skeleton_dir . '/' . $source_file ] = $bedrock_dir . '/' . Strings::replace_cases( $skeleton_name, $project_name, $source_file );
		}

		$output->writeln( "\n<info> ➤ Copying files</info>\n" );

		foreach ( $transfers as $source => $destination ) {
			$destination_dir = dirname( $destination );

			if ( ! is_dir( $destination_dir ) ) {
				Filesystem::mkdir( $destination_dir );
			}

			$content = file_get_contents( $source );
			$content = Strings::replace_cases( $skeleton_name, $project_name, $content );

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

		$output->writeln( "\n<info> ➤ Moving files to the final destination</info>\n" );

		$clean = array_values( array_filter(
			glob( $root_dir . '/{,.}*', GLOB_BRACE ),
			function ( $file ) {
				return is_file( $file ) && ! str_ends_with( $file, '/README.md' );
			},
		) );

		foreach ( $clean as $file ) {
			Filesystem::delete( $root_dir . '/' . $file );
		}

		$files = array_filter( Filesystem::list_files( $bedrock_dir ), function ( $file ) {
			return 'README.md' !== $file;
		} );

		foreach ( $files as $file ) {
			Filesystem::move( $bedrock_dir . '/' . $file, $root_dir . '/' . $file );
		}

		$gitignore = file_get_contents( $root_dir . '/.gitignore' );
		$gitignore = str_replace( 'web/app/mu-plugins/*/', "\n!web/app/mu-plugins/$project_name/", $gitignore );
		file_put_contents( $root_dir . '/.gitignore', $gitignore );

		Filesystem::delete( $root_dir . '/bedrock' );
		Filesystem::delete( $root_dir . '/skeleton' );
		Filesystem::delete( $root_dir . '/installer' );

		$message = <<<EOT
<info> ➤ DONE</info>

<info>All is ready to do some work!</info>

<comment>Go to the project directory first:</comment>
cd $project_name

<comment>Start your ddev server with command:</comment>
ddev start

<comment>Start developing frontend assets with command:</comment>
npm run start

<comment>Build frontend assets for production with command:</comment>
npm run build

<comment>Generate pot file with command:</comment>
composer run make-pot

<comment>After translating the pot file, generate json files for frontend translations with command:</comment>
composer run make-json

<info>Happy development!</info>

EOT;
		$output->writeln( $message );
	}
}
