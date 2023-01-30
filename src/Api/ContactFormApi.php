<?php

namespace WpifySkeleton\Api;

use WpifySkeleton\Managers\ApiManager;
use WpifySkeleton\Repositories\ContactRepository;
use WpifySkeleton\Settings;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use Wpify\Templates\TwigTemplates;

/**
 * Blog API
 */
class ContactFormApi extends WP_REST_Controller {
	const ACTION_SUBMIT = 'contact-form/submit';

	public function __construct(
		private TwigTemplates $twig,
		private Settings $settings,
		private ContactRepository $contact_repository,
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
					'name'    => array(
						'required' => true,
					),
					'email'   => array(
						'required' => true,
					),
					'message' => array(
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

		$contact = $this->contact_repository->create();

		$contact->name    = $request->get_param( 'name' );
		$contact->company = $request->get_param( 'company' );
		$contact->email   = $request->get_param( 'email' );
		$contact->phone   = $request->get_param( 'phone' );
		$contact->message = $request->get_param( 'message' );
		$contact->title   = sprintf( '%s - %s - %s', $contact->name, $contact->email, date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( 'now' ) ) );

		$this->contact_repository->save( $contact );

		$items = array(
			array(
				'label' => _x( 'Name', 'contact form', 'wpify-skeleton' ),
				'value' => $contact->name,
			),
			array(
				'label' => _x( 'Company', 'contact form', 'wpify-skeleton' ),
				'value' => $contact->company,
			),
			array(
				'label' => _x( 'E-mail', 'contact form', 'wpify-skeleton' ),
				'value' => $contact->email,
			),
			array(
				'label' => _x( 'Phone', 'contact form', 'wpify-skeleton' ),
				'value' => $contact->phone,
			),
			array(
				'label' => _x( 'Message', 'contact form', 'wpify-skeleton' ),
				'value' => $contact->message,
			),
		);

		$subject = sprintf( _x( 'New contact from web by %s', 'contact form', 'wpify-skeleton' ), $contact->name );

		$args    = array(
			'subject'       => $subject,
			'headline'      => _x( 'You received a new contact!', 'contact form', 'wpify-skeleton' ),
			'introduction'  => _x( 'A new contact was sent from your website from Contact form. Please find details below:', 'contact form', 'wpify-skeleton' ),
			'content_title' => _x( 'Content of the form:', 'contact form', 'wpify-skeleton' ),
			'items'         => $items,
			'outro'         => _x( "That's all we have for you for now, please contact potential customer and good luck!\n\nAlways yours WPify", 'contact form', 'wpify-skeleton' ),
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
