<?php

namespace WpifySkeleton\Blocks;

use WpifySkeleton\Api\ContactFormApi;
use WpifySkeleton\Managers\ApiManager;
use Wpify\Asset\AssetFactory;
use Wpify\CustomFields\CustomFields;
use Wpify\PluginUtils\PluginUtils;
use Wpify\Templates\TwigTemplates;

class ContactBlock {
	public function __construct(
		private CustomFields $wcf,
		private TwigTemplates $template,
		private ApiManager $api_manager,
		private PluginUtils $utils,
		private AssetFactory $asset_factory,
	) {
		$this->wcf->create_gutenberg_block(
			array(
				'name'            => 'wpify-skeleton/contact',
				'title'           => __( 'Contact', 'wpify-skeleton' ),
				'render_callback' => array( $this, 'render' ),
				'items'           => array(
					array(
						'type'  => 'text',
						'id'    => 'section_id',
						'title' => __( 'Section id', 'wpify-skeleton' )
					),
					array(
						'type'              => 'textarea',
						'id'                => 'title',
						'title'             => __( 'Title', 'wpify-skeleton' ),
						'custom_attributes' => array(
							'rows' => 2
						)
					),
					array(
						'type'  => 'wysiwyg',
						'id'    => 'consent',
						'title' => __( 'Consent text', 'wpify-skeleton' ),
					),
					array(
						'type'  => 'text',
						'id'    => 'cta',
						'title' => __( 'CTA label', 'wpify-skeleton' ),
					),
					array(
						'type'  => 'wysiwyg',
						'id'    => 'success_message',
						'title' => __( 'Success message', 'wpify-skeleton' ),
					),
					array(
						'type'  => 'wysiwyg',
						'id'    => 'error_message',
						'title' => __( 'Error message', 'wpify-skeleton' ),
					),
				),
			)
		);
	}

	public function render( array $block_attributes ) {
		if ( isset( $_GET['context'] ) && $_GET['context'] === 'edit' ) {
			ob_start();
			?>
            <section class="section">
                <div class="container">
                    <div class="contact">
                        <h2 class="title"><?php _e( 'Contact form', 'wpify-skeleton' ) ?></h2>
                    </div>
                </div>
            </section>
			<?php
			return ob_get_clean();
		}

		$block_attributes['api'] = $this->api_manager->get_rest_url( ContactFormApi::ACTION_SUBMIT );

		$this->asset_factory->wp_script( $this->utils->get_plugin_path( 'build/contact-form.js' ), array(
			'in_footer'         => true,
			'text_domain'       => $this->utils->get_text_domain(),
			'translations_path' => $this->utils->get_plugin_path( 'languages/json' ),
			'variables'         => array(),
		) );

		return $this->template->render( 'blocks/contact', null, array(
				'block' => $block_attributes,
				'app'   => wp_json_encode( $block_attributes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
			)
		);
	}
}
