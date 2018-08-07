<?php

/*
Plugin Name: WP RAWB Image Service Invalidation
Plugin URI: http://24hr.se
Description: 
Version: 0.0.4
Author: Camilo Tapia <camilo.tapia@24hr.se>
*/

// don't load directly
if ( !defined( 'ABSPATH' ) ) {

    die( '-1' );

} else {

    $dir = dirname( __FILE__ );

    define('RAWBImageServiceInvalidationVersion', '0.0.4');

    require_once( $dir . '/lib/invalidate.php');

    function rawb_image_service_invalidation_init() {

        // Init or use instance of the manager.
        $dir = dirname( __FILE__ );

        if(class_exists( 'RAWBImageServiceInvalidation' )){
            $rawb_image_service_invalidation = new RAWBImageServiceInvalidation($dir, RAWBImageServiceInvalidationVersion);
        }

    }

    add_action( 'init', 'rawb_image_service_invalidation_init');

}
