<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/13/14
 * Time: 12:28 PM
 *
 * File Description : Contains all functions related to customers in braintree
 *
 * 1)CREATE CUSTOMER
 */

/**
 * Function to create a customer in vault
 *
 * @param $user_name : array('first_name'=>'','last_name'=>'','email'=>)
 *
 * @return array
 */
function create_customer( $user_name ) {

    $result = Braintree_Customer::create( array(
        'firstName' => $user_name[ 'first_name' ],
        'lastName' => $user_name[ 'last_name' ],
        'email' => $user_name[ 'email' ]
    ) );

    if ( $result->success ) {

        return array( 'success' => true, 'customer_id' => $result->customer->id );
    } else {
        return array( 'success' => false );
    }
}

/**
 * Function to get all details of the customer stored in the vault
 * @param braintree $customer_id
 */
function get_customer_credit_card_details( $customer_id ) {

    //$customer_id = '81538496';

    $customer = Braintree_Customer::find( $customer_id );

    $customer_credit_card_data = customer_credit_card_details( $customer->creditCards );

    return $customer_credit_card_data;

}

function customer_credit_card_details( $credit_cards ) {

    if ( empty( $credit_cards ) )
        return array('card_exists' => false);

    $credit_card_details[ 'customer_name' ] = $credit_cards[ 0 ]->cardholderName;
    $credit_card_details[ 'card_number' ] = $credit_cards[ 0 ]->maskedNumber;
    $credit_card_details[ 'expiration_date' ] = $credit_cards[ 0 ]->expirationDate;
    $credit_card_details[ 'card_exists' ] = true;

    return $credit_card_details;


}