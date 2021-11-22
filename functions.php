<?php
defined('ABSPATH') or die("No script kiddies please!");

if ( !class_exists('OTECH_Class') ) {

	class OTECH_Class {

    function __construct() {
    	$this->includes();
    }

    public function includes() {
    	include(get_template_directory() . '/inc/class-helper.php');
    	include(get_template_directory() . '/inc/class-back.php');
    	include(get_template_directory() . '/inc/class-front.php'); 
        include(get_template_directory() . '/inc/class-enqueue.php');
    }
	}

	$otechObj = new OTECH_Class();
}
?>