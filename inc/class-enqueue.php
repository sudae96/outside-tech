<?php
defined('ABSPATH') or die("No script kiddies please!");

if ( !class_exists('Enqueue_Class') ) {

	class Enqueue_Class {

        function __construct() {
            add_action('wp_enqueue_scripts', [ $this, 'register_frontend_assets'] );
        }

        function register_frontend_assets() {
            wp_enqueue_style( 'otech-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'), 'all' );
            wp_enqueue_script( 'otech-scripts', get_template_directory_uri() . '/assets/script.js', array( 'jquery' ), '4.3.1', true );
            wp_localize_script( 'otech-scripts', 'otech_obj', array(
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('uwcc-frontend-ajax-nonce')
            ));
        }
    }
    new Enqueue_Class();
}