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
    $subscription_data[ 'plan_id' ] = $subscription->planId;
    $subscription_data[ 'subscription_id' ] = $subscription_id;
    $subscription_data[ 'price' ] = $subscription->price;
    $subscription_data[ 'start_date' ] = $subscription->firstBillingDate->format( 'd/m/Y' );

    if ( !empty( $subscription->billingPeriodStartDate ) ||
        !empty( $subscription->billingPeriodEndDate )
    ) {
        $subscription_data[ 'bill_start' ] = $subscription->billingPeriodStartDate->format( 'd/m/Y' );
        $subscription_data[ 'bill_end' ] = $subscription->billingPeriodEndDate->format( 'd/m/Y' );
    }


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
        'planId' => $plan_id,
        'merchantAccountId' => DEFAULT_MERCHANT_ACCOUNTID
        
    ) );

    if ( $create_subscription->success ) {
        return array( 'code' => 'OK', 'subscription_id' => $create_subscription->subscription->id );

    } else {
        $error_msg = array( code => 'ERROR', 'msg' => $create_subscription->message );
        return $error_msg;
    }
}

/**
 * Function to cancel active subscription in  braintree
 * @param $domain_id
 * @return array
 */
function cancel_subscription_in_braintree( $subscription_id ) {

    #check if a active subscription exits
    try {
        Braintree_Subscription::find( $subscription_id );

        $cancel_subscription = Braintree_Subscription::cancel( $subscription_id );
        if ( $cancel_subscription->success ) {

            return array( 'code' => 'OK' );
        } else {

            $error_msg = array( 'code' => 'ERROR', 'msg' => 'Subscription not cancelled ' );
            return $error_msg;
        }
    } catch ( Braintree_Exception_NotFound $e ) {

        return array( 'code' => 'ERROR', 'msg' => 'No existing subscription' );
    }

}

/**
 * Function to create a subscription with a future billing start date, when user goes from a
 * higher priced plan to a lower priced plan. The subscription status in braintree will be set
 * to pending till the user is billed
 *
 * @param $card_token
 * @param $plan_id
 * @param $new_billing_date
 * @return array containing subscription id on success or error msg on failure
 */
function create_pending_subscription_in_braintree( $card_token, $plan_id, $new_billing_date ) {

    $create_subscription = Braintree_Subscription::create( array(
        'paymentMethodToken' => $card_token,
        'planId' => $plan_id,
        'firstBillingDate' => $new_billing_date,
        'merchantAccountId' => DEFAULT_MERCHANT_ACCOUNTID
    ) );

    if ( $create_subscription->success ) {
        return array( 'code' => 'OK', 'subscription_id' => $create_subscription->subscription->id );

    } else {
        $error_msg = array( code => 'ERROR', 'msg' => $create_subscription->message );
        return $error_msg;
    }
}

/**
 * Function to update a subscription when moving from lower to higher price plan
 *
 * @param $subscription_id
 * @param $card_token
 * @param $plan_id
 * @param $plan_price
 * @return array containing subscription id on success or error msg on failure
 */
function update_subscription_in_braintree( $subscription_id, $card_token, $plan_id, $plan_price ) {

    $update_subscription = Braintree_Subscription::update( $subscription_id, array(
        'paymentMethodToken' => $card_token,
        'price' => $plan_price,
        'planId' => $plan_id,
        'options' => array( 'prorateCharges' => true )
    ) );

    if ( $update_subscription->success ) {
        return array( 'code' => 'OK');

    } else {
        $error_msg = array( code => 'ERROR', 'msg' => $update_subscription->message );
        return $error_msg;
    }
}


/**
 * Function to get complete subscription details based on the subscription Id
 *
 * @param $subscription_id
 * @return mixed
 */
function get_complete_subscription_details( $subscription_id ) {

    $subscription = new stdClass();
    try {
        $subscription = Braintree_Subscription::find( $subscription_id );
        $subscription->success = 1;
        $subscription->errormsg = 'No errors in fetching subscription details';
    } catch (Braintree_Exception_NotFound $e) {
        $subscription->success = 0;
        $subscription->errormsg = $e->getMessage();
    }

    return $subscription;
}