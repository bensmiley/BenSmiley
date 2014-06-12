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

    $current_user_id = get_current_user_id();

    $user_domains = get_current_user_domains( $current_user_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $user_domains ) );
}

add_action( 'wp_ajax_fetch-user-domains', 'ajax_fetch_user_domains' );


function ajax_create_user_domain() {

    $domain_details = $_POST;

    $domain_data = create_user_domain( $domain_details );

    wp_send_json( array( 'code' => 'OK', 'data' => $domain_data ) );
}

add_action( 'wp_ajax_create-user-domain', 'ajax_create_user_domain' );

function ajax_update_user_domain() {

    $domain_data = $_POST;

    update_domain_post( $domain_data );

    //update_domain_post_meta($domain_data);

    $domain_data = get_user_domain_details( $domain_data[ 'ID' ] );

    wp_send_json( array( 'code' => 'OK', 'data' => $domain_data ) );

}

add_action( 'wp_ajax_update-user-domain', 'ajax_update_user_domain' );