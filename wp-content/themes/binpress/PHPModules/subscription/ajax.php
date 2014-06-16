<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/16/14
 * Time: 6:20 PM
 */

require_once 'functions.php';

//TODO: write proper coments in foiles
/**
 * Function to fetch the subscription details for each domain
 */
function ajax_read_subscription() {

    // retreive the domain Id through GET request
    $domain_id = $_GET[ 'domain_id' ];

    $domain_details = get_subscription_details_for_domain( $domain_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $domain_details ) );
}

add_action( 'wp_ajax_read-subscription', 'ajax_read_subscription' );
