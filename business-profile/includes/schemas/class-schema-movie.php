<?php
/**
 * Create a schema for a Movie as listed on schema.org.
 *
 * @package   BusinessProfile
 * @copyright Copyright (c) 2019, Five Star Plugins
 * @license   GPL-2.0+
 * @since     2.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'bpfwpSchemaMovie' ) ) :

	/**
	 * Movie schema for Business Profile
	 *
	 * @since 2.0.0
	 */
	class bpfwpSchemaMovie extends bpfwpSchema {

		/**
		 * The name used by Schema.org
		 *
		 * @since  2.0.0
		 * @access public
		 * @var    string
		 */
		public $slug = 'Movie';

		/**
		 * The display name for this schema
		 *
		 * @since  2.0.0
		 * @access public
		 * @var    string
		 */
		public $name = 'Movie';


		/**
		 * Load the schema's default fields
		 *
		 * @since  2.0.0
		 * @access public
		 * @return void
		 */
		public function set_fields() {
			require_once BPFWP_PLUGIN_DIR . '/includes/schemas/class-schema-field.php';

			$fields = array(
				new bpfwpSchemaField( array( 
					'slug' 				=> 'itemListElement', 
					'name' 				=> 'Item List Element', 
					'type'				=> 'ItemList',
					'input'				=> 'SchemaField',
					'children' 			=> array (
						new bpfwpSchemaField( array( 
							'slug' 				=> 'listItem', 
							'name' 				=> 'List Item', 
							'type'				=> 'ListItem',
							'repeatable'		=> true,
							'input'				=> 'SchemaField',
							'children' 			=> array (
								new bpfwpSchemaField( array( 
									'slug' 				=> 'position', 
									'name' 				=> 'Position', 
									'input' 			=> 'text',
									'callback'			=> apply_filters( 'bpfwp_schema_field_callback', null, 'position', $this->slug, 'itemListElement', 'ListItem' )
								) ),
								new bpfwpSchemaField( array( 
									'slug' 				=> 'item', 
									'name' 				=> 'Item', 
									'type'				=> 'Movie',
									'input'				=> 'SchemaField',
									'children' 			=> array (
										new bpfwpSchemaField( array(
											'slug' 				=> 'name', 
											'name' 				=> 'Name', 
											'input' 			=> 'text',
											'callback' 			=> apply_filters( 'bpfwp_schema_field_callback', null, 'name', $this->slug, 'itemListElement', 'ListItem', 'item' )
										) ),
										new bpfwpSchemaField( array( 
											'slug' 				=> 'url', 
											'name' 				=> 'URL', 
											'input' 			=> 'url',
											'callback'			=> apply_filters( 'bpfwp_schema_field_callback', null, 'url', $this->slug, 'itemListElement', 'ListItem', 'item' )
										) ),
										new bpfwpSchemaField( array( 
											'slug' 				=> 'image', 
											'name' 				=> 'Image', 
											'input' 			=> 'text',
											'callback' 			=> apply_filters( 'bpfwp_schema_field_callback', null, 'image', $this->slug, 'itemListElement', 'ListItem', 'item' )
										) ),
										new bpfwpSchemaField( array(
											'slug' 				=> 'dateCreated', 
											'name' 				=> 'Date Created', 
											'input' 			=> 'text',
											'callback' 			=> apply_filters( 'bpfwp_schema_field_callback', null, 'dateCreated', $this->slug, 'itemListElement', 'ListItem', 'item' )
										) ),
										new bpfwpSchemaField( array( 
											'slug' 				=> 'director', 
											'name' 				=> 'Director', 
											'type'				=> 'Person',
											'input'				=> 'SchemaField',
											'children' 			=> array (
												new bpfwpSchemaField( array( 
													'slug' 				=> 'name', 
													'name' 				=> 'Name', 
													'input' 			=> 'text',
													'callback'			=> apply_filters( 'bpfwp_schema_field_callback', null, 'name', $this->slug, 'itemListElement', 'ListItem', 'item', 'director' )
												) )
											)
										) ),
										new bpfwpSchemaField( array( 
											'slug' 				=> 'review', 
											'name' 				=> 'Review', 
											'type'				=> 'Review',
											'input'				=> 'SchemaField',
											'children' 			=> array (
												new bpfwpSchemaField( array( 
													'slug' 				=> 'reviewRating', 
													'name' 				=> 'Rating', 
													'type'				=> 'Rating',
													'input'				=> 'SchemaField',
													'children' 			=> array (
														new bpfwpSchemaField( array( 
															'slug' 				=> 'ratingValue', 
															'name' 				=> 'User Rating', 
															'input' 			=> 'text',
															'callback'			=> apply_filters( 'bpfwp_schema_field_callback', null, 'ratingValue', $this->slug, 'itemListElement', 'ListItem', 'item', 'review', 'reviewRating' )
														) ),
													)
												) ),					
												new bpfwpSchemaField( array( 
													'slug' 				=> 'author', 
													'name' 				=> 'Author', 
													'type'				=> 'Person',
													'input'				=> 'SchemaField',
													'children' 			=> array (
														new bpfwpSchemaField( array( 
															'slug' 				=> 'name', 
															'name' 				=> 'Name', 
															'input' 			=> 'text',
															'callback'			=> apply_filters( 'bpfwp_schema_field_callback', null, 'name', $this->slug, 'itemListElement', 'ListItem', 'item', 'review', 'author' )
														) )
													)
												) ),
												new bpfwpSchemaField( array(
													'slug' 				=> 'reviewBody', 
													'name' 				=> 'Review Body', 
													'input' 			=> 'text',
													'callback' 			=> apply_filters( 'bpfwp_schema_field_callback', null, 'reviewBody', $this->slug, 'itemListElement', 'ListItem', 'item', 'review' )
												) ),
												new bpfwpSchemaField( array( 
													'slug' 				=> 'aggregateRating', 
													'name' 				=> 'Aggregate Rating', 
													'type'				=> 'AggregateRating',
													'input'				=> 'SchemaField',
													'children' 			=> array (
														new bpfwpSchemaField( array( 
															'slug' 				=> 'ratingValue', 
															'name' 				=> 'Rating', 
															'input' 			=> 'text',
															'callback'			=> apply_filters( 'bpfwp_schema_field_callback', null, 'ratingValue', $this->slug, 'itemListElement', 'ListItem', 'item', 'review', 'aggregateRating' )
														) ),
														new bpfwpSchemaField( array( 
															'slug' 				=> 'ratingCount', 
															'name' 				=> 'Rating Count', 
															'input' 			=> 'text',
															'callback'			=> apply_filters( 'bpfwp_schema_field_callback', null, 'ratingCount', $this->slug, 'itemListElement', 'ListItem', 'item', 'review', 'aggregateRating' )
														) ),
														new bpfwpSchemaField( array( 
															'slug' 				=> 'bestRating', 
															'name' 				=> 'Best Rating', 
															'input' 			=> 'text',
															'callback'			=> apply_filters( 'bpfwp_schema_field_callback', null, 'bestRating', $this->slug, 'itemListElement', 'ListItem', 'item', 'review', 'aggregateRating' )
														) )
													)
												) ),
											)
										) ),
									)
								) ),
							)
						) ),
					)
				) ),
			);

			$this->fields = apply_filters( 'bpfwp_schema_fields', $fields, $this->slug );
		}


		/**
		 * Load the schema's child classes
		 *
		 * @since  2.0.0
		 * @access public
		 * @return void
		 */
		public function initialize_children(  $depth ) {
			$depth--;

			$child_classes = array ();

			foreach ( $child_classes as $slug => $name ) {
				require_once BPFWP_PLUGIN_DIR . '/includes/schemas/class-schema-' . $slug . '.php';

				$class_name = 'bpfwpSchema' . $name;
				$this->children[$slug] = new $class_name( array( 'depth' => $depth ) );
			}
		}

	}
endif;