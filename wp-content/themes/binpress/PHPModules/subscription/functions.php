<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/16/14
 * Time: 6:22 PM
 */

/**
 * Function to query the subscription table and return the query result
 *
 * @param $domain_id
 * @return array containing the latest subscription data for the domainId if found else empty array()
 */
function query_subscription_table( $domain_id ) {

    global $wpdb;

    $query = "SELECT * FROM subscription WHERE domain_id = " . $domain_id . " ORDER BY id DESC LIMIT 0, 1";

    $query_result = $wpdb->get_results( $query, ARRAY_A );

    if ( is_null( $query_result ) )
        return array();

    return $query_result[ 0 ];
}

/**
 * Function to get subscription deatils of the domain id passed
 * @param $domain_id
 * @return array
 */
function get_subscription_details_for_domain( $domain_id ) {

    $subscription_data = query_subscription_table( $domain_id );

    if ( is_null( $subscription_data ) ) {
        return array();
    }
    if ( $subscription_data[ 'subscription_id' ] == "BENAJFREE" ) {
        $subscription_details = get_free_subscription_data( $subscription_data );
        return $subscription_details;
    }

    $subscription_details = braintree_subscription_data( $subscription_data );
    return $subscription_details;

}

/**
 * Function to get all details of subscription having free plan
 *
 * @param $subscription_details
 */
function get_free_subscription_data( $subscription_details ) {

    $subscription_data[ 'start_date' ] = date( 'd/m/Y', strtotime( $subscription_details[ 'datetime' ] ) );
    $subscription_data[ 'subscription_id' ] = "BENAJFREE";
    $subscription_data[ 'plan_name' ] = 'Free';
    $subscription_data[ 'plan_id' ] = 'dm8w';
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

/**
 * Function to create a free subscription for
 *
 * every new domain registered by the user
 *
 * @param $domain_id
 */
function create_free_subscription( $domain_id ) {

    global $wpdb;

    $table_name = 'subscription';

    $date_time = date( 'Y-m-d H:i:s' );

    $wpdb->insert( $table_name,
        array(
            'domain_id' => $domain_id,
            'subscription_id' => 'BENAJFREE',
            'datetime' => $date_time,
            'status' => 'active'
        ) );
}

/**
 * Function to create a subscription entry for
 *
 * a domain after a subscription has been made in braintree after payment
 *
 * @param $domain_id , $subscription_id
 */
function create_subscription( $domain_id, $new_subscription_id, $old_subscription_id ) {

    global $wpdb;

    // cancel the currently active subscription in the db
    cancel_subscription( $old_subscription_id );

    $table_name = 'subscription';

    $date_time = date( 'Y-m-d H:i:s' );

    $wpdb->insert( $table_name,
        array(
            'domain_id' => $domain_id,
            'subscription_id' => $new_subscription_id,
            'datetime' => $date_time,
            'status' => 'active'
        ) );
}

/**
 * Function to create a subscription entry for
 *
 * a domain after a pending subscription has been made in braintree
 *
 * @param $domain_id , $subscription_id
 */
function create_pending_subscription( $domain_id, $subscription_id ) {

    global $wpdb;

    $table_name = 'subscription';

    $date_time = date( 'Y-m-d H:i:s' );

    $wpdb->insert( $table_name,
        array(
            'domain_id' => $domain_id,
            'subscription_id' => $subscription_id,
            'datetime' => $date_time,
            'status' => 'pending'
        ) );
}

/**
 * Function to cancel the currently active subscription in the db
 *
 * @param $current_subscription
 */
function cancel_subscription( $old_subscription_id ) {
    global $wpdb;

    $wpdb->update( 'subscription', array( 'status' => 'canceled' ),
        array( 'ID' => $old_subscription_id ) );
}

