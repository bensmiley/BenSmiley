<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/9/14
 * Time: 3:41 PM
 */

require 'functions.php';
//TODO: wtite rpoper comments
/**
 * Function to update the user display details in the user profile page
 */
function ajax_fetch_user_domains() {

    $current_user_id = get_current_user_id( );

    $user_domains = get_current_user_domains($current_user_id);

    wp_send_json( array( 'code' => 'OK','data'=>$user_domains ) );
}

add_action( 'wp_ajax_fetch-user-domains', 'ajax_fetch_user_domains' );



function ajax_create_user_domain() {

    $domain_details = $_POST;

    $domain_data = create_user_domain($domain_details);

    wp_send_json( array( 'code' => 'OK','data'=>$domain_data ) );
}

add_action( 'wp_ajax_create-user-domain', 'ajax_create_user_domain' );