<?php

namespace WpifySkeleton\PostTypes;

use Wpify\CustomFields\CustomFields;
use Wpify\PluginUtils\PluginUtils;

class ContactPostType {
	const KEY = 'contact';

	public function __construct( private PluginUtils $utils, private CustomFields $custom_fields ) {
		add_action( 'init', array( $this, 'register_post_type' ) );

		$this->add_capabilities();
		$this->register_custom_fields();
	}

	public function register_post_type(): void {
		register_post_type(
			self::KEY,
			array(
				'label'           => __( 'Contact', 'wpify-skeleton' ),
				'labels'          => array(
					'name'                  => _x( 'Contacts', 'Post Type General Name', 'wpify-skeleton' ),
					'singular_name'         => _x( 'Contact', 'Post Type Singular Name', 'wpify-skeleton' ),
					'menu_name'             => __( 'Contacts', 'wpify-skeleton' ),
					'name_admin_bar'        => __( 'Contact', 'wpify-skeleton' ),
					'archives'              => __( 'Contact Archives', 'wpify-skeleton' ),
					'attributes'            => __( 'Contact Attributes', 'wpify-skeleton' ),
					'parent_item_colon'     => __( 'Parent contact:', 'wpify-skeleton' ),
					'all_items'             => __( 'All contacts', 'wpify-skeleton' ),
					'add_new_item'          => __( 'Add New contact', 'wpify-skeleton' ),
					'add_new'               => __( 'Add New', 'wpify-skeleton' ),
					'new_item'              => __( 'New contact', 'wpify-skeleton' ),
					'edit_item'             => __( 'Edit contact', 'wpify-skeleton' ),
					'update_item'           => __( 'Update contact', 'wpify-skeleton' ),
					'view_item'             => __( 'View contact', 'wpify-skeleton' ),
					'view_items'            => __( 'View contacts', 'wpify-skeleton' ),
					'search_items'          => __( 'Search contact', 'wpify-skeleton' ),
					'not_found'             => __( 'Not found', 'wpify-skeleton' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'wpify-skeleton' ),
					'featured_image'        => __( 'Featured Image', 'wpify-skeleton' ),
					'set_featured_image'    => __( 'Set featured image', 'wpify-skeleton' ),
					'remove_featured_image' => __( 'Remove featured image', 'wpify-skeleton' ),
					'use_featured_image'    => __( 'Use as featured image', 'wpify-skeleton' ),
					'insert_into_item'      => __( 'Insert into contact', 'wpify-skeleton' ),
					'uploaded_to_this_item' => __( 'Uploaded to this contact', 'wpify-skeleton' ),
					'items_list'            => __( 'Contacts list', 'wpify-skeleton' ),
					'items_list_navigation' => __( 'Contacts list navigation', 'wpify-skeleton' ),
					'filter_items_list'     => __( 'Filter contacts list', 'wpify-skeleton' ),
				),
				'description'     => __( 'Contacts from the contact form.', 'wpify-skeleton' ),
				'public'          => false,
				'hierarchical'    => false,
				'show_ui'         => true,
				'menu_position'   => 50,
				'menu_icon'       => 'dashicons-email-alt',
				'capability_type' => array( 'contact', 'contacts' ),
				'supports'        => array( 'title' ),
			)
		);
	}

	public function register_custom_fields() {
		$this->custom_fields->create_metabox( array(
			'id'         => self::KEY . '_contact_info',
			'title'      => __( 'Contact Info', 'wpify-skeleton' ),
			'context'    => 'advanced',
			'priority'   => 'high',
			'post_types' => array( self::KEY ),
			'items'      => array(
				array(
					'id'    => 'name',
					'label' => __( 'Name', 'wpify-skeleton' ),
					'type'  => 'text',
				),
				array(
					'id'    => 'company',
					'label' => __( 'Company', 'wpify-skeleton' ),
					'type'  => 'text',
				),
				array(
					'id'    => 'email',
					'label' => __( 'E-mail', 'wpify-skeleton' ),
					'type'  => 'email',
				),
				array(
					'id'    => 'phone',
					'label' => __( 'Phone', 'wpify-skeleton' ),
					'type'  => 'tel',
				),
				array(
					'id'    => 'message',
					'label' => __( 'Message', 'wpify-skeleton' ),
					'type'  => 'textarea',
				),
			),
		) );
	}

	public function add_capabilities() {
		$key     = 'wpify_skeleton_activated';
		$version = $this->utils->get_plugin_version();

		if ( get_option( 'wpify_skeleton_' . self::KEY . '_cap' ) === $version ) {
			return;
		}

		$roles = array( 'administrator' );

		foreach ( $roles as $role ) {
			$role = get_role( $role );

			$role->add_cap( sprintf( 'edit_%s', self::KEY ) );
			$role->add_cap( sprintf( 'read_%s', self::KEY ) );
			$role->add_cap( sprintf( 'delete_%s', self::KEY ) );
			$role->add_cap( sprintf( 'edit_%ss', self::KEY ) );
			$role->add_cap( sprintf( 'edit_others_%ss', self::KEY ) );
			$role->add_cap( sprintf( 'publish_%ss', self::KEY ) );
			$role->add_cap( sprintf( 'read_private_%ss', self::KEY ) );
			$role->add_cap( sprintf( 'edit_%ss', self::KEY ) );
		}

		update_option( $key, $version );
	}
}