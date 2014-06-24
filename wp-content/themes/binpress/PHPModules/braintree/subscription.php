<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/16/14
 * Time: 9:08 PM
 */


/**
 * Function to get all the subscription details based on the subscription Id
 *
 * @param $subscription_id
 * @return mixed
 */
function get_subscription_details( $subscription_id ) {

    $subscription = Braintree_Subscription::find( $subscription_id );

    $plan_data = get_plan_by_id( $subscription->planId );

    $plan_name = $plan_data->name;

    $subscription_data[ 'plan_name' ] = $plan_name;
    $subscription_data[ 'price' ] = $subscription->price;
    $subscription_data[ 'start_date' ] = $subscription->firstBillingDate->format( 'd/m/Y' );
    $subscription_data[ 'bill_start' ] = $subscription->billingPeriodStartDate->format( 'd/m/Y' );
    $subscription_data[ 'bill_end' ] = $subscription->billingPeriodEndDate->format( 'd/m/Y' );

    return $subscription_data;
}

/**
 * Function to create a subscription for a user domain in braintree
 *
 * @param $credit_card_token
 * @param $plan_id
 * @return array containing subscription id on success or error msg on failure
 */
function create_subscription_in_braintree( $credit_card_token, $plan_id ) {
    $create_subscription = Braintree_Subscription::create( array(
        'paymentMethodToken' => $credit_card_token,
        'planId' => $plan_id
    ) );

    if ( $create_subscription->success ) {
        return array( 'code' => 'OK', 'subscription_id' => $create_subscription->subscription->id );

    } else {
        $error_msg = array( code => 'ERROR', 'msg' => $create_subscription->message );
        return $error_msg;
    }
}