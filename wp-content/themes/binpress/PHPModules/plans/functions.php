<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/14/14
 * Time: 1:23 PM
 *
 * File Description :  Contains list of functions for plans
 */


function get_plan_name_for_domain( $domain_id ) {

    $subscription_data = get_subscription_details_for_domain( $domain_id );

    $plan_name = $subscription_data[ 'plan_name' ];

    return $plan_name;

}


/***************************************************OLD *************/
/**
 * required
 * Function to return the plan name and id for the domain in case of free plan
 *
 * @param $post_id
 * @return mixed|null|WP_Error
 */
function get_plan_name_id( $post_id ) {

    $plan_data = wp_get_post_terms( $post_id, 'plan' );

    $plan = array();

    foreach ( $plan_data as $value ) {
        $plan[ 'id' ] = $value->term_id;
        $plan[ 'name' ] = $value->name;
    }

    return $plan;
}

/**
 * Function to fetch the domain data for one domain registered under
 *
 * the current logged in user
 */
function ajax_read_plan_for_domain() {

    // retreive the plan Id through GET request
    $plan_id = $_GET[ 'plan_id' ];
    $domain_id = $_GET[ 'domain_id' ];

    $plan_details = get_plan_by_id( $plan_id, $domain_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $plan_details ) );
}

add_action( 'wp_ajax_read-plan', 'ajax_read_plan_for_domain' );

// get plan details for each domain
function get_plan_by_id( $plan_id, $domain_id ) {

    global $wpdb;

    $plan_data = get_term( $plan_id, 'plan', ARRAY_A );

    $plan_details = array();

    foreach ( $plan_data as $key => $value ) {
        if ( $key == "name" )
            $plan_details[ 'plan_name' ] = $value;
    }

    $query = "SELECT * FROM subscription WHERE domain_id = " . $domain_id . " ORDER BY id DESC LIMIT 0, 1";

    $active_subscription = $wpdb->get_results( $query, ARRAY_A );
    $plan_details[ 'start_time' ] = date( "d/m/Y", strtotime( $active_subscription[ 0 ][ 'datetime' ] ) );
    $plan_details[ 'payment' ] = 0;
    $plan_details[ 'billing_cycle' ] = 'N/A';
//    echo '<pre>';
//    print_r($active_subscription);
    return $plan_details;

}


function ajax_get_current_domain_plan_id() {

    $domain_id = $_GET[ 'domain_id' ];

    $plan_id = 'as1';

    wp_send_json( array( 'code' => 'OK', 'data' => array( 'id' => $plan_id ) ) );
}

add_action( 'wp_ajax_get-current-domain-plan-id', 'ajax_get_current_domain_plan_id' );