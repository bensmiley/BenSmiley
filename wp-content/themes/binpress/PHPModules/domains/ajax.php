<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/9/14
 * Time: 3:41 PM
 *
 * File Description :  Contains a list of ajax functions for user domains on the dashboard
 */

require 'functions.php';

/**
 * Function to fetch all the domains registered under the current logged in user
 */
function ajax_fetch_user_domains() {

    $user_domains = get_current_user_domains();

    wp_send_json( array( 'code' => 'OK', 'data' => $user_domains ) );
}

add_action( 'wp_ajax_fetch-user-domains', 'ajax_fetch_user_domains' );

/**
 * Function to fetch the domain data for one domain registered under
 *
 * the current logged in user
 */
function ajax_read_user_domain() {

    // retreive the domain Id through GET request
    $domain_id = $_GET[ 'ID' ];

    $domain_details = get_user_domain_details( $domain_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $domain_details ) );
}

add_action( 'wp_ajax_read-user-domain', 'ajax_read_user_domain' );

/**
 * Function to create a new domain under the current user
 *
 * accepts the POST data as arguments
 */
function ajax_create_user_domain() {

    $domain_details = $_POST;

    $domain_id = create_user_domain( $domain_details );

//    if($domain_id['code'] == "ERROR")
//        wp_send_json( array( 'code' => 'ERROR', 'msg' => $domain_id[ 'msg' ] ) );

    $domain_data = get_user_domain_details( $domain_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $domain_data ) );
}

add_action( 'wp_ajax_create-user-domain', 'ajax_create_user_domain' );

/**
 * Function to update the domain details
 */
function ajax_update_user_domain() {

    $domain_data = $_POST;

    update_domain_post( $domain_data );

    $domain_data = get_user_domain_details( $domain_data[ 'ID' ] );

    wp_send_json( array( 'code' => 'OK', 'data' => $domain_data ) );

}

add_action( 'wp_ajax_update-user-domain', 'ajax_update_user_domain' );

/**
 * Function to delete the domain details
 */
function ajax_delete_user_domain() {

    $domain_id = $_POST[ 'ID' ];

    delete_domain( $domain_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $domain_id ) );
}

add_action( 'wp_ajax_delete-user-domain', 'ajax_delete_user_domain' );