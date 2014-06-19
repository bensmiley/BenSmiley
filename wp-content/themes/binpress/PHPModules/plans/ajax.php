<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/17/14
 * Time: 12:23 PM
 * File Description :  Contains a list of ajax functions for plans for a domain
 *
 */

require "functions.php";

/**
 * Function to fetch all the plans available for the domains
 */
function ajax_fetch_all_plans() {

    $braintree_plans = get_all_plans();

    wp_send_json( array( 'code' => 'OK', 'data' => $braintree_plans ) );
}

add_action( 'wp_ajax_fetch-all-plans', 'ajax_fetch_all_plans' );


/**
 * Function to return a single plan data based on the plan id passed
 *
 */
function ajax_read_plan_by_plan_id() {

    // retreive the plan Id through GET request
    $plan_id = $_GET[ 'plan_id' ];

    $plan_details = get_plan_data_by_plan_id( $plan_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $plan_details ) );
}

add_action( 'wp_ajax_read-plan', 'ajax_read_plan_by_plan_id' );