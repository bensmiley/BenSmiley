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
 * Function to the customer billing details. Makes a call to the braintree API
 * and fetches the customer credit card details
 *
 */
function ajax_read_user_payment() {

    // retreive the user id through GET request
    $user_id = $_GET[ 'ID' ];

    //get the braintree customer id for the user
    $braintree_customer_id = get_user_meta( $user_id, 'braintree_customer_id', true );

    // get the credit card info for the customer
    $customer_credit_card_data = get_customer_credit_card_details( $braintree_customer_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $customer_credit_card_data ) );
}

add_action( 'wp_ajax_read-user-payment', 'ajax_read_user_payment' );

/**
 * Function to make a payment by the user, when credit card used for the first time.
 *
 * The credit card array passed to function  create_credit_card_for_customer() is n the format:
 *
 *     [creditCardData] => Array
        (
            [creditCardNumber] => "encrypted value"
            [cardholderName] => "encrypted value"
            [expirationDate] => "encrypted value"
            [creditCardCvv] => "encrypted value"
        )
 *
 */
function ajax_user_new_payment(){

    $credit_card_data = $_POST;

    $card_data = $credit_card_data[ 'creditCardData' ];

    unset($credit_card_data['action']);

    $plan_id = $credit_card_data[ 'planId' ];

    $domain_id = $credit_card_data[ 'domainId' ];

    $card_token = create_credit_card_for_customer($card_data);
    if($card_token['code'] == 'ERROR')
        wp_send_json( array( 'code' => 'OK', 'msg' => $card_token['msg']) );

    $subscription = create_subscription_in_braintree( $card_token['credit_card_token'], $plan_id );
    if($subscription['code'] == 'ERROR')
        wp_send_json( array( 'code' => 'OK', 'msg' => $subscription['msg']) );

    create_subscription( $domain_id , $subscription['subscription_id'] );
    wp_send_json( array( 'code' => 'OK', 'msg' => 'Payment Processed') );

}
add_action( 'wp_ajax_user-new-payment', 'ajax_user_new_payment' );