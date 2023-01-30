<?php

namespace WpifySkeleton\Api;

use WpifySkeleton\Managers\ApiManager;
use WpifySkeleton\Settings;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use Wpify\PluginUtils\PluginUtils;
use Wpify\Templates\TwigTemplates;

/**
 * Blog API
 */
class ContactFormApi extends WP_REST_Controller {
	const ACTION_SUBMIT = 'contact-form/submit';

	/**
	 * Construct
	 */
	public function __construct(
		private TwigTemplates $twig,
		private Settings $settings,
	) {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			ApiManager::PATH,
			self::ACTION_SUBMIT,
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'handle_submit' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'name' => array(
						'required' => true,
					),
					'email'    => array(
						'required' => true,
					),
					'message'  => array(
						'required' => true,
					),
				),
			),
		);
	}

	/**
	 * Returns products for Your Selection app
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
	public function handle_submit( WP_REST_Request $request ): WP_REST_Response {
		// honeypot protection
		if ( ! empty( $request->get_param( 'firstname' ) ) ) {
			return new WP_REST_Response( 'error', 422 );
		}

		$name    = $request->get_param( 'name' );
		$company = $request->get_param( 'company' );
		$email   = $request->get_param( 'email' );
		$phone   = $request->get_param( 'phone' );
		$message = $request->get_param( 'message' );
		$subject = sprintf( _x( 'New contact from web by %s', 'contact form', 'bomma' ), $name );
		$items   = array(
			array(
				'label' => _x( 'Name', 'contact form', 'bomma' ),
				'value' => $name,
			),
			array(
				'label' => _x( 'Company', 'contact form', 'bomma' ),
				'value' => $company,
			),
			array(
				'label' => _x( 'E-mail', 'contact form', 'bomma' ),
				'value' => $email,
			),
			array(
				'label' => _x( 'Phone', 'contact form', 'bomma' ),
				'value' => $phone,
			),
			array(
				'label' => _x( 'Message', 'contact form', 'bomma' ),
				'value' => $message,
			),
		);
		$args    = array(
			'subject'       => $subject,
			'headline'      => _x( 'You received a new contact!', 'contact form', 'bomma' ),
			'introduction'  => _x( 'A new contact was sent from your website from Contact form. Please find details below:', 'contact form', 'bomma' ),
			'content_title' => _x( 'Content of the form:', 'contact form', 'bomma' ),
			'items'         => $items,
			'outro'         => _x( "That's all we have for you for now, please contact potential customer and good luck!\n\nAlways yours WPify", 'contact form', 'bomma' ),
		);
		$body    = $this->twig->render( 'emails/contact', null, $args );
		$to      = $this->settings->get_option( Settings::FORM_EMAIL );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$result  = wp_mail( $to, $subject, $body, $headers );

		if ( $result ) {
			return new WP_REST_Response( 'ok', 200 );
		}

		return new WP_REST_Response( compact( 'result', 'to', 'subject', 'body', 'headers' ), 422 );
	}
}
