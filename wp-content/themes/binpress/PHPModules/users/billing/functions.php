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
 * when user moves to higher priced  plan
 *
 * @param $card_token
 * @param $domain_id
 * @param $plan_id
 */
function make_active_subscription( $subscription_array ) {

    $current_subscription_id = $subscription_array[ 'current_subscription_id' ];
    $plan_id = $subscription_array[ 'selected_plan_id' ];
    $plan_name = $subscription_array[ 'selected_plan_name' ];
    $plan_price = $subscription_array[ 'selected_plan_price' ];
    $card_token = $subscription_array[ 'card_token' ];
    $domain_id = $subscription_array[ 'domain_id' ];

    // make a new subscription if free plan
    if ( $current_subscription_id == "BENAJFREE" ) {
        $subscription = create_subscription_in_braintree( $card_token, $plan_id );
        if ( $subscription[ 'code' ] == 'ERROR' )
            wp_send_json( array( 'code' => 'OK', 'msg' => $subscription[ 'msg' ] ) );

        // make the subscription entry in the database
        create_subscription( $domain_id, $subscription[ 'subscription_id' ], $current_subscription_id );

    } else {
        $subscription = update_subscription_in_braintree( $current_subscription_id, $card_token, $plan_id, $plan_price );
        if ( $subscription[ 'code' ] == 'ERROR' )
            wp_send_json( array( 'code' => 'OK', 'msg' => $subscription[ 'msg' ] ) );

    }

    // add the new  plan as a term for domain post and update plan id for domain
    wp_set_post_terms( $domain_id, $plan_name, 'plan' );
    update_post_meta( $domain_id, 'plan_id', $plan_id );

    wp_send_json( array( 'code' => 'OK', 'msg' => 'Payment Processed' ) );
}

/**
 *  Function to create a subscription with a future billing start date, when user goes from a
 * higher priced plan to a lower priced plan. The subscription status is set to pending
 *
 * @param $subscription_array
 */
function make_pending_subscription( $subscription_array ) {

    //Last active subscription from subscription table
    $current_subscription_id = $subscription_array[ 'current_subscription_id' ];

    $plan_id = $subscription_array[ 'selected_plan_id' ];
    $card_token = $subscription_array[ 'card_token' ];
    $domain_id = $subscription_array[ 'domain_id' ];

    // get the last billing date of the current subscription
    $subscription_data = get_subscription_details( $current_subscription_id );
    $bill_end_date = $subscription_data[ 'bill_end' ];

    // convert date to d-m-Y format since datetime does not recognise d/m/Y for conversion
    $bill_end_date = str_replace( '/', '-', $bill_end_date );
    $new_billing_date = new DateTime( $bill_end_date );

    $pending_subscription = create_pending_subscription_in_braintree( $card_token, $plan_id, $new_billing_date );
    if ( $pending_subscription[ 'code' ] == 'ERROR' )
        wp_send_json( array( 'code' => 'OK', 'msg' => $pending_subscription[ 'msg' ] ) );

    //Cancel the current subscription from braintree
    $cancel_subscription = cancel_subscription_in_braintree( $current_subscription_id );

    if ( $cancel_subscription[ 'code' ] === 'OK' ) {

        // make the pending subscription entry in the database
        create_pending_subscription( $domain_id, $pending_subscription[ 'subscription_id' ] );

        //Add pending and current subscription ids to subscription_cron_job table , so that their status can later be updated to active and cancelled respectively
        create_subscription_cron_job( $current_subscription_id, $pending_subscription[ 'subscription_id' ], $bill_end_date );

        wp_send_json( array( 'code' => 'OK', 'msg' => 'New Subscription Successful' ) );
    } 
    else
        wp_send_json( array( 'code' => 'ERROR', 'msg' => $cancel_subscription[ 'msg' ] ) );
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

    $selected_plan_data = get_plan_data_by_plan_id( $selected_plan_id );
    $active_plan_data = get_plan_data_by_plan_id( $active_plan_id );

    $selected_plan_price = round( $selected_plan_data[ 'price' ] );
    $active_plan_price = round( $active_plan_data[ 'price' ] );

    if ( $selected_plan_price > $active_plan_price )
        return true;

    return false;

}

/***
 * Function to get the list of all subscription to be cancelled using cron
 */
function get_cancel_subscription_list() {

    global $wpdb;

    $table_name = 'subscription_cron_jobs';

    $sql = "SELECT * FROM " . $table_name . " WHERE status = '1' AND cancel_date = CURDATE()";

    $query_result = $wpdb->get_results( $sql, ARRAY_A );

    if ( empty ( $query_result ) )
        return array();
    else
        return $query_result[ 0 ];
}

/**
 * Function to update the status of the subscription entry
 *
 * @param $subscription_record_id
 */
function update_subscription_table( $cancel_cron_job_id, $old_subscription_id, $new_subscription_id ) {

    global $wpdb;

    $subscription_table = 'subscription';
    $cron_job_table = 'subscription_cron_jobs';

    //update status of old_subscription_id from 'active' to 'cancelled'
    $wpdb->update( $subscription_table, array( 'status' => 'cancelled' ), array( 'subscription_id' => $old_subscription_id , 'status'=>'active') );

    //update status of new_subscription_id from 'pending' to 'active'
    $wpdb->update( $subscription_table, array( 'status' => 'active' ), array( 'subscription_id' => $new_subscription_id, 'status'=>'pending' ) );

    //update status of cron job table to 0
    $wpdb->update( $cron_job_table, array( 'status' => '0' ), array( 'id' => $cancel_cron_job_id ) );
   
}

/**
 * Function to enter a cron job entry for pending subscriptions
 *
 * @param $current_subscription_id, $new_subscription_id , $bill_end_date
 */

function create_subscription_cron_job( $current_subscription_id, $new_subscription_id, $bill_end_date ) {

    global $wpdb;

    $cancel_date = date( 'Y-m-d', strtotime( $bill_end_date ) );

    $table_name = 'subscription_cron_jobs';

    $subscription_details = array( 'old_subscription_id' => $current_subscription_id,
        'new_subscription_id' => $new_subscription_id,
        'cancel_date' => $cancel_date,
        'status' => '1' );

    $wpdb->insert( $table_name, $subscription_details );

}