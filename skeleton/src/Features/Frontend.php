<?php // phpcs:ignore

namespace WpifySkeleton\Features;

use WpifySkeleton\Managers\ApiManager;
use WpifySkeletonDeps\Wpify\Asset\AssetFactory;
use WpifySkeletonDeps\Wpify\PluginUtils\PluginUtils;

/**
 * Frontend
 */
class Frontend {
	const PRIMARY_MENU = 'primary_menu';
	const FOOTER_MENU = 'footer_menu';
    const FOOTER_SIDEBAR = 'footer_sidebar';

	/**
	 * Construct
	 *
	 * @param PluginUtils $utils Utils.
	 * @param AssetFactory $asset_factory AssetFactory.
	 */
	public function __construct(
		private PluginUtils $utils,
		private AssetFactory $asset_factory,
        private ApiManager $api_manager
	) {
		add_action( 'wp_footer', array( $this, 'inject_browsersync' ), PHP_INT_MAX );
		add_action( 'after_setup_theme', array( $this, 'add_theme_supports' ) );
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );
		add_action( 'widgets_init', array( $this, 'register_widget_areas' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'setup_assets' ), 100 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'fix_svg_images' ), 10, 3 );
		add_shortcode( 'year', array( $this, 'year_shortcode' ) );
	}

	/**
	 * Add theme support.
	 *
	 * @return void
	 */
	public function add_theme_supports() {
		add_theme_support( 'custom-logo' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'widgets' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
	}

	public function register_menus() {
		register_nav_menus( array(
			self::PRIMARY_MENU => __( 'Primary Menu', 'wpify-skeleton' ),
			self::FOOTER_MENU  => __( 'Footer Menu', 'wpify-skeleton' ),
		) );
	}

    public function register_widget_areas() {
        register_sidebar( array(
	        'name'          => __( 'Footer', 'wpify-skeleton' ),
	        'id'            => self::FOOTER_SIDEBAR,
	        'before_widget' => '<div class="site-widget">',
	        'after_widget'  => '</div>',
	        'before_title'  => '<h3 class="site-widget__title">',
	        'after_title'   => '</h3>',
        ) );
    }

	/**
	 * Setup assets
	 *
	 * @return void
	 */
	public function setup_assets() {
		$this->asset_factory->wp_script(
			$this->utils->get_plugin_path( 'web/app/themes/wpify-skeleton/build/plugin.js' ),
			array(
				'text_domain'       => $this->utils->get_text_domain(),
				'translations_path' => $this->utils->get_plugin_path( 'languages/json' ),
				'variables'         => array(
					'wpify-skeleton' => array(
						'api_url' => get_rest_url( null, ApiManager::PATH ),
					),
				),
				'in_footer'         => true,
			)
		);
		$this->asset_factory->wp_script( $this->utils->get_plugin_path( 'web/app/themes/wpify-skeleton/build/plugin.css' ) );

        if (function_exists('WC')) {
	        $this->asset_factory->wp_script(
		        $this->utils->get_plugin_path( 'web/app/themes/wpify-skeleton/build/mini-cart.js' ),
		        array(
			        'in_footer'         => true,
			        'text_domain'       => $this->utils->get_text_domain(),
			        'translations_path' => $this->utils->get_plugin_path( 'languages/json' ),
			        'variables'         => array(),
			        'dependencies'      => array( 'wc-cart-block-frontend', 'wc-blocks-data-store' ),
		        )
	        );
	        $this->asset_factory->wp_script(
		        $this->utils->get_plugin_path( 'web/app/themes/wpify-skeleton/build/side-cart.js' ),
		        array(
			        'in_footer'         => true,
			        'text_domain'       => $this->utils->get_text_domain(),
			        'translations_path' => $this->utils->get_plugin_path( 'languages/json' ),
			        'variables'         => array(
				        'side_cart' => array(
					        'cart_url'     => wc_get_cart_url(),
					        'checkout_url' => wc_get_checkout_url(),
				        ),

			        ),
			        'dependencies'      => array( 'wc-cart-block-frontend', 'wc-blocks-data-store' ),
		        )
	        );
	        $this->asset_factory->wp_script(
		        $this->utils->get_plugin_path( 'web/app/themes/wpify-skeleton/build/search.js' ),
		        array(
			        'in_footer'         => true,
			        'text_domain'       => $this->utils->get_text_domain(),
			        'translations_path' => $this->utils->get_plugin_path( 'languages/json' ),
			        'variables'         => array(
				        'search' => array(
					        'api_url'      => $this->api_manager->get_rest_url(),
					        'checkout_url' => wc_get_checkout_url(),
				        ),

			        ),
			        'dependencies'      => array( 'wc-cart-block-frontend', 'wc-blocks-data-store' ),
		        )
	        );
        }

	}

	/**
	 * Inject BrowserSync
	 *
	 * @return void
	 */
	public function inject_browsersync() {
		if ( wp_get_environment_type() === 'development' ) {
			?>
            <script id="__bs_script__">
              document.write('<script async src=\'https://HOST:3000/browser-sync/browser-sync-client.js?v=2.27.10\'><\/script>'.replace('HOST', location.hostname));
            </script>
			<?php
		}
	}

	public function year_shortcode() {
		return date( 'Y' );
	}

	public function fix_svg_images( $attr, $attachment, $size ) {
		if ( str_ends_with( $attr['src'], '.svg' ) ) {
			unset( $attr['sizes'] );
			unset( $attr['srcset'] );
		}

		return $attr;
	}
}
