<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/25/14
 * Time: 10:10 AM
 *
 * File Description :  Contains a list of functions for the billing of user domains
 */

/**
 * Function to create a new active subscription and make a payment for it
 *
 * @param $card_token
 * @param $domain_id
 * @param $plan_id
 */
function make_active_subscription( $subscription_array ) {

    $current_subscription_id = $subscription_array[ 'current_subscription_id' ];
    $plan_id = $subscription_array[ 'selected_plan_id' ];
    $plan_name = $subscription_array[ 'selected_plan_name' ];
    $card_token = $subscription_array[ 'card_token' ];
    $domain_id = $subscription_array[ 'domain_id' ];

    $subscription = create_subscription_in_braintree( $card_token, $plan_id );
    if ( $subscription[ 'code' ] == 'ERROR' )
        wp_send_json( array( 'code' => 'OK', 'msg' => $subscription[ 'msg' ] ) );

    // cancel the previous active subscription for the domain in braintree
    $cancel_subscription = cancel_active_subscription_in_braintree( $current_subscription_id );
    if ( $cancel_subscription[ 'code' ] == 'ERROR' )
        wp_send_json( array( 'code' => 'OK', 'msg' => $subscription[ 'msg' ] ) );

    // make the subscription entry in the database
    create_subscription( $domain_id, $subscription[ 'subscription_id' ], $current_subscription_id );

    // add the new  plan as a term for domain post
    wp_set_post_terms( $domain_id, $plan_name, 'plan' );

    wp_send_json( array( 'code' => 'OK', 'msg' => 'Payment Processed' ) );
}

/**
 *  Function to create a subscription with a future billing start date, when user goes from a
 * higher priced plan to a lower priced plan. The subscription status is set to pending
 *
 * @param $subscription_array
 */
function make_pending_subscription( $subscription_array ) {

    $current_subscription_id = $subscription_array[ 'current_subscription_id' ];
    $plan_id = $subscription_array[ 'selected_plan_id' ];
    $card_token = $subscription_array[ 'card_token' ];
    $domain_id = $subscription_array[ 'domain_id' ];

    // get the last billing date of the current subscription
    $subscription_data = get_subscription_details( $current_subscription_id );
    $bill_end_date = $subscription_data[ 'bill_end' ];

    // convert date to d-m-Y format since strtotime does not recognise d/m/Y for conversion
    $bill_end_date = str_replace( '/', '-', $bill_end_date );
    $new_billing_date = date( 'd/m/Y', strtotime( '+1 day', strtotime( $bill_end_date ) ) );

    $pending_subscription = create_pending_subscription_in_braintree( $card_token, $plan_id, $new_billing_date );
    if ( $pending_subscription[ 'code' ] == 'ERROR' )
        wp_send_json( array( 'code' => 'OK', 'msg' => $pending_subscription[ 'msg' ] ) );

    // make the pending subscription entry in the database
    create_pending_subscription( $domain_id, $pending_subscription[ 'subscription_id' ] );

    wp_send_json( array( 'code' => 'OK', 'msg' => 'Subscription Successful' ) );
}

/**
 * Function to compare the price of selected and active plans.
 *
 * If the price of selected plan is greater than price of active plan return true
 * else return false.
 *
 * @param $selected_plan_id
 * @param $active_plan_id
 * @return bool true | false
 */
function compare_plan_price( $selected_plan_id, $active_plan_id ) {

    $selected_plan_id = "pmpro_2";
    $active_plan_id = "pmpro_3";

    $selected_plan_data = get_plan_data_by_plan_id( $selected_plan_id );
    $active_plan_data = get_plan_data_by_plan_id( $active_plan_id );

    $selected_plan_price = round( $selected_plan_data[ 'price' ] );
    $active_plan_price = round( $active_plan_data[ 'price' ] );

    if ( $selected_plan_price > $active_plan_price )
        return true;

    return false;

}