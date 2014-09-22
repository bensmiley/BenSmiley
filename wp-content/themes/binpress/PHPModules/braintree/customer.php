<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/13/14
 * Time: 12:28 PM
 *
 * File Description : Contains all functions related to customers in braintree
 *
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
 * Function to get credit card details of a customer stored in the vault
 *
 * @param braintree (int) $customer_id
 *
 * @return empty array with key card_exists set to false, if no credit card found.
 *         if credit card found for user, returns the card details along with key
 *         card_exists set to true
 */
function get_customer_credit_card_details( $customer_id ) {

    //$customer_id = '81538496';

    $customer = Braintree_Customer::find( $customer_id );

    if ( empty( $customer->creditCards ) )
        return array( 'card_exists' => false,
            'customer_id' => $customer_id,
            'braintree_client_token' => generate_client_token() );

    $customer_credit_card_data = customer_credit_card_details( $customer->creditCards );

    $customer_credit_card_data[ 'braintree_customer_id' ] = $customer_id;

    return $customer_credit_card_data;

}


/**
 * Function to check if credit card exists for customer and returns card details
 *
 * @param array $credit_cards from braintree
 *
 * @return empty array with key card_exists set to false, if no credit card found.
 *         if credit card found for user, returns the card details along with key
 *         card_exists set to true
 */
function customer_credit_card_details( $credit_cards ) {

    if ( empty( $credit_cards ) )
        return array( 'card_exists' => false );

    $credit_card_details[ 'customer_name' ] = $credit_cards[ 0 ]->cardholderName;
    $credit_card_details[ 'card_number' ] = $credit_cards[ 0 ]->maskedNumber;
    $credit_card_details[ 'expiration_date' ] = $credit_cards[ 0 ]->expirationDate;
    $credit_card_details[ 'token' ] = $credit_cards[ 0 ]->token;
    $credit_card_details[ 'card_exists' ] = true;

    return $credit_card_details;


}

/**
 * Function to create a credit card for a customer in the vault
 *
 * @param $credit_card_data
 * @return array containing credit card token on success and error msg on failure
 */
function create_credit_card_for_customer( $credit_card_data ) {

    $create_card = Braintree_Customer::update( $credit_card_data[ 'braintree_customer_id' ], array(
        'creditCard' => array(
            'cardholderName' => $credit_card_data[ 'cardholderName' ],
            'number' => $credit_card_data[ 'creditCardNumber' ],
            'expirationDate' => $credit_card_data[ 'expirationDate' ],
            'cvv' => $credit_card_data[ 'creditCardCvv' ],
            'options' => array(
                'verifyCard' => true
            )
        )
    ) );

    if ( $create_card->success ) {
        $credit_card_token = $create_card->customer->creditCards[ 0 ]->token;
        $success_msg = array( 'code' => 'OK', 'credit_card_token' => $credit_card_token );
        return $success_msg;

    } else {
        $error_msg = array( 'code' => 'ERROR', 'msg' => $create_card->message );
        return $error_msg;
    }


}