<?php
defined('ABSPATH') or die("No script kiddies please!");

if ( !class_exists('Helper_Class') ) {

	class Helper_Class {

        static function sanitize_array($array = array(), $sanitize_rule = array()) {
          if ( !is_array($array) || count($array) == 0 ) {
              return array();
          }

          foreach ( $array as $k => $v ) {
              if ( !is_array($v) ) {
                  $default_sanitize_rule = (is_numeric($k)) ? 'html' : 'text';
                  $sanitize_type = isset($sanitize_rule[ $k ]) ? $sanitize_rule[ $k ] : $default_sanitize_rule;
                  $array[ $k ] = self:: sanitize_value($v, $sanitize_type);
              }

              if ( is_array($v) ) {
                  $array[ $k ] = self:: sanitize_array($v, $sanitize_rule);
              }
          }

          return $array;
        }

        public function pr($ar) {
            echo '<pre>';
            print_r($ar);
            echo '</pre>';
        } 
    }
    new Helper_Class();
}