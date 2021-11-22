<?php
defined('ABSPATH') or die("No script kiddies please!");

if ( !class_exists('Admin_Class') ) {

	class Admin_Class extends Helper_Class {

        function __construct() {
            add_action('init', [$this, 'register_post_type']);
            add_action('init', [$this, 'event_register_taxonomies']);

        
            add_action('rest_api_init', [$this, 'wp_rest_user_endpoints']); //Register rest routes

            //Add Event Custom Fields To `Event` post type
            add_action('admin_init', [$this, 'add_post_meta_box']);
            add_action('save_post', [$this, 'save_post_meta_box']);
        }

        /**
        * Register Custom Post Type
        */
        public function register_post_type() {

            register_post_type('event', [
                'labels' => [
                    'name' => 'Events',
                    'singular_name' => 'Event',
                    'add_new_item' => 'Add New',
                    'add_new_item' => 'Add New Event',
                    'edit_item' => 'Edit Event',
                    'new_item' => 'New Event',
                    'view_item' => 'View Event',
                    'view_items' => 'View Events',
                    'search_items' => 'Search Events',
                    'not_found' => 'No events found.',
                    'not_found_in_trash' => 'No events found in trash.',
                    'all_items' => 'All Events',
                    'archives' => 'Event Archives',
                    'insert_into_item' => 'Insert into Event.',
                    'upload_to_this_item' => 'Upload to this Event',
                    'filter_items_list' => 'Filter Events List',
                    'items_list_navigation' => 'Events list navigation.',
                    'items_list' => 'Events List',
                    'item_published' => 'Event published.',
                    'item_published_privately' => 'Event published privately.',
                    'item_reverted_to_draft' => 'Event reverted to draft.',
                    'item_scheduled' => 'Event scheduled.',
                    'item_updated' => 'Event updated.',
                ],
                'has_archive' => true,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_rest' => true,
                'supports' => ['title', 'editor', 'thumbnail', 'revisions', 'custom-fields'],
                'can_export' => true,
                'menu_icon' => 'dashicons-welcome-write-blog'
            ]);
        }

        function event_register_taxonomies() {
            // Register Event Type Taxonomies
            $labels = array(
                'name' => _x( 'Event Types', 'taxonomy general name' ),
                'singular_name' => _x( 'Event Type', 'taxonomy singular name' ),
                'search_items' =>  __( 'Search Event Types' ),
                'all_items' => __( 'All Event Types' ),
                'parent_item' => __( 'Parent Event Type' ),
                'parent_item_colon' => __( 'Parent Event Type:' ),
                'edit_item' => __( 'Edit Event Type' ), 
                'update_item' => __( 'Update Event Type' ),
                'add_new_item' => __( 'Add New Event Type' ),
                'new_item_name' => __( 'New Event Type Name' ),
                'menu_name' => __( 'Event Types' ),
            );    
         
            // Now register the taxonomy
            register_taxonomy('event_types',array('event'), array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_in_rest' => true,
                'show_ui' => true,
                'show_tagcloud' => true,
            ));

            $taglabels = array(
                'name'                       => 'Event Tag',
                'singular_name'              => 'Event Tag',
                'menu_name'                  => 'Event Tags',
                'all_items'                  => 'All Event Tags',
                'parent_item'                => 'Parent Event Tag',
                'parent_item_colon'          => 'Parent Event Tag:',
                'new_item_name'              => 'New Event Tag',
                'add_new_item'               => 'Add New Event Tag',
                'edit_item'                  => 'Edit Event Tag',
                'update_item'                => 'Update Event Tag',
                'separate_items_with_commas' => 'Separate Event Tags with commas',
                'search_items'               => 'Search Event Tags',
                'add_or_remove_items'        => 'Add or remove Event Tags',
                'choose_from_most_used'      => 'Choose from the most used Event Tags',
                'not_found'                  => 'Not Found',
            );
            $args = array(
                'hierarchical' => false,
                'labels' => $taglabels,
                'show_in_rest' => true,
                'show_ui' => true,
                'show_tagcloud' => true,
            );
            register_taxonomy( 'event_tags', array( 'event' ), $args );
        }

        public function add_post_meta_box() {
            add_meta_box(
                'event_post_metadata', //div id containing rendered fields
                'Event Details', // Section Heading Displayed as Text
                [$this, 'events_metabox_fields_callback'], // Callback function to render fields
                'event', // name of post type on which to render field
                'side', //location on screen
                'low' // placement priority
            );
        }

        public function events_metabox_fields_callback() {
            global $post;
            $event_data = get_post_custom($post->ID);
            $event_data = maybe_unserialize($event_data['event_data'][0]);

            wp_nonce_field( 'metabox_configuration_nonce', 'metabox_process' );
            include_once get_template_directory() . '/inc/meta-fields.php';
        }

        public function save_post_meta_box($postid) {
            if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return;
            }

            if ( isset($_POST[ 'metabox_process' ]) && wp_verify_nonce($_POST[ 'metabox_process' ], 'metabox_configuration_nonce') ) {
          update_post_meta($postid, 'event_data', $_POST[ 'event' ]);
            } else {
                return;
            }
        }

        function wp_rest_user_endpoints($request) {
            register_rest_route('wp/v2', 'users/register', array(
                'methods' => 'POST',
                'callback' => 'user_register_endpoint_handler',
            ));
        }

        function user_register_endpoint_handler($request = null) {
            
        }

    }
    new Admin_Class();
}