<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/16/14
 * Time: 6:22 PM
 */

//TODO: write proper coments in foiles
/**
 * Function to get subscription deatils of the domain id passed
 * @param $domain_id
 * @return array
 */
function get_subscription_details_for_domain( $domain_id ) {

    global $wpdb;

    $query = "SELECT * FROM subscription WHERE domain_id = " . $domain_id . " ORDER BY id DESC LIMIT 0, 1";

    $query_result = $wpdb->get_results( $query, ARRAY_A );

    if ( is_null( $query_result ) )
        return array();

    if ( $query_result[ 0 ][ 'subscription_id' ] == "BENAJFREE" ) {
        $subscription_data = get_free_subscription_data( $query_result[ 0 ] );
        return $subscription_data;
    }

    $subscription_data = braintree_subscription_data( $query_result[ 0 ] );
    return $subscription_data;

}

/**
 * Function to get all details of subscription having free plan
 *
 * @param $subscription_details
 */
function get_free_subscription_data( $subscription_details ) {

    $subscription_data[ 'start_date' ] = date( 'd/m/Y', strtotime( $subscription_details[ 'datetime' ] ) );
    $subscription_data[ 'name' ] = 'Free';
    $subscription_data[ 'price' ] = '0';
    $subscription_data[ 'bill_start' ] = 'N/A';
    $subscription_data[ 'bill_end' ] = 'N/A';

    return $subscription_data;

}

/**
 * Function to get all details of subscription having free plan
 *
 * @param $subscription_details
 */
function braintree_subscription_data( $subscription_details ) {

    $subscription_data = get_subscription_details( $subscription_details[ 'subscription_id' ] );
    return $subscription_data;

}