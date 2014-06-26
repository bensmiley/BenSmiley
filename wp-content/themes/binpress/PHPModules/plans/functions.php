<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/14/14
 * Time: 1:23 PM
 *
 * File Description :  Contains list of functions for plans
 */

/**
 * not used
 * Function to get the plan name for the domain passed
 *
 * @param $domain_id
 * @return string plan name
 */
function get_plan_details_for_domain( $domain_id ) {

    $subscription_data = get_subscription_details_for_domain( $domain_id );

    $plan_name = $subscription_data['active_subscription'][ 'plan_name' ];
    $plan_id = $subscription_data['active_subscription'][ 'plan_id' ];

    $plan_data = array('plan_name' =>$plan_name,'plan_id' => $plan_id);

    return $plan_data;

}

/**
 * Function to return the plan details based on the plan id passed
 *
 * @param $plan_id
 * @return array of plan details
 */

function get_plan_data_by_plan_id( $plan_id ) {

    // calls the braintree plan function and returns the plan object
    $plan_data = get_plan_by_id( $plan_id );

    $plan_details[ 'plan_id' ] = $plan_data->id;
    $plan_details[ 'plan_name' ] = $plan_data->name;
    $plan_details[ 'description' ] = $plan_data->description;
    $plan_details[ 'price' ] = $plan_data->price;

    return $plan_details;
}
