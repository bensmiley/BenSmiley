<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/13/14
 * Time: 12:28 PM
 */

/**
 * Function to create a customer in vault
 *
 * @param $user_name : array('first_name'=>'','last_name'=>'')
 *
 * @return array
 */
function create_customer( $user_name ) {

    $result = Braintree_Customer::create( array(
        'firstName' => $user_name[ 'first_name' ],
        'lastName' => $user_name[ 'last_name' ]
    ) );

    if ( $result->success ) {

        return array( 'success' => true, 'customer_id' => $result->customer->id );
    } else {
        return array( 'success' => false );
    }
}