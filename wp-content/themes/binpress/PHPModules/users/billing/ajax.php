<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/19/14
 * Time: 5:29 PM
 *
 * File Description :  Contains a list of ajax functions for the billing of user domains
 */

/**
 * Function to the customer billing details
 *
 */
function ajax_read_user_payment() {

    // retreive the user id through GET request
    $user_id = $_GET[ 'ID' ];

    //get the braintree customer id for the user
    $braintree_customer_id = get_user_meta( $user_id, 'braintree_customer_id', true );

    $customer_credit_card_data = get_customer_credit_card_details( $braintree_customer_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $customer_credit_card_data ) );
}

add_action( 'wp_ajax_read-user-payment', 'ajax_read_user_payment' );