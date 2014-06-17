<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/16/14
 * Time: 9:08 PM
 */

function get_subscription_details( $subscription_id ) {

    $subscription = Braintree_Subscription::find( $subscription_id );

    $plan_name = get_plan_name_by_id($subscription->planId);

    $subscription_data[ 'name' ] = $plan_name;
    $subscription_data[ 'price' ] = $subscription->price;
    $subscription_data[ 'start_date' ] = $subscription->firstBillingDate;
    $subscription_data[ 'bill_start' ] = $subscription->billingPeriodStartDate;
    $subscription_data[ 'bill_end' ] = $subscription->billingPeriodEndDate;

    return $subscription_data;
}