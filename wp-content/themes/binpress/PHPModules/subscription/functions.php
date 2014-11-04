<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/16/14
 * Time: 6:22 PM
 */

//TODO :  check if function can be merged with the query function below
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

    // check if the last subscription is pending or active
    if ( isset($query_result[ 0 ]) && ($query_result[ 0 ][ 'status' ] == "pending") ) {

        // if($query_result[ 0 ][ 'subscription_id' ] !== "BENAJFREE"){
        // No need to exclude BENAJFREE as it could also be in pending state

        //get one row from table starting from the 2nd row
        //LIMIT 1,1 -> offset=1 :starting from 2nd row , row count to display = 1: display 1 row
        $query2 = "SELECT * FROM subscription WHERE domain_id = " . $domain_id . " ORDER BY id DESC LIMIT 1, 1";

        //returns last active subscription
        $last_active_subscription = $wpdb->get_results( $query2, ARRAY_A );
        return $last_active_subscription[ 0 ];
        

    }

    return isset($query_result[ 0 ]) ? $query_result[ 0 ] : array();
}

/**
 * Function to query the subscription table and return the subscription id for the domain
 *
 * @param $domain_id
 * @return array containing the active|pending subscription id for the domainId if found else empty array()
 */
function get_subscription_id_for_domain( $domain_id ) {

    global $wpdb;

    $query = "SELECT * FROM subscription WHERE domain_id = " . $domain_id . " ORDER BY id DESC LIMIT 0, 1";

    $lastest_subscription = $wpdb->get_results( $query, ARRAY_A );

    if ( is_null( $lastest_subscription ) )
        return array();

    // check if the last subscription is pending or active
    if ( $lastest_subscription[ 0 ][ 'status' ] == "pending" ) {
        $query2 = "SELECT * FROM subscription WHERE domain_id = " . $domain_id . " ORDER BY id DESC LIMIT 1, 1";

        $last_active_subscription = $wpdb->get_results( $query2, ARRAY_A );
        $subscription_data[ 'pending_subscription_id' ] = $lastest_subscription[ 0 ][ 'subscription_id' ];
        $subscription_data[ 'subscription_id' ] = $last_active_subscription[ 0 ][ 'subscription_id' ];

        return $subscription_data;
    }

    $subscription_data[ 'subscription_id' ] = $lastest_subscription[ 0 ][ 'subscription_id' ];
    $subscription_data[ 'datetime' ] = $lastest_subscription[ 0 ][ 'datetime' ];

    return $subscription_data;
}


/**
 * Function to get subscription deatils of the domain id passed
 * @param $domain_id
 * @return array
 */
function get_subscription_details_for_domain( $domain_id ) {

    $subscription_data = get_subscription_id_for_domain( $domain_id );

    if ( is_null( $subscription_data ) ) {
        return array();
    }
    if ( $subscription_data[ 'subscription_id' ] == "BENAJFREE" ) {
        $subscription_details[ 'active_subscription' ] = get_free_subscription_data( $subscription_data );
        return $subscription_details;
    }

    $subscription_details[ 'active_subscription' ] = get_subscription_details( $subscription_data[ 'subscription_id' ] );
    if ( isset( $subscription_data[ 'pending_subscription_id' ] ) ){
        if($subscription_data[ 'pending_subscription_id' ]!=="BENAJFREE")
            $subscription_details[ 'pending_subscription' ] = get_subscription_details( $subscription_data[ 'pending_subscription_id' ] );
        else{
            $subscription_data[ 'previous_active_enddate' ]  = $subscription_details[ 'active_subscription' ][ 'bill_end' ];
            $subscription_details[ 'pending_subscription' ] = get_free_subscription_data( $subscription_data );
        }
    }


    return $subscription_details;

}

/**
 * Function to get all details of subscription having free plan
 *
 * @param $subscription_details
 */
function get_free_subscription_data( $subscription_details ) {
    
    $subscription_data[ 'start_date' ] = date( 'd/m/Y', strtotime( $subscription_details[ 'datetime' ] ) );
    if (isset($subscription_details[ 'previous_active_enddate' ])) {
        $subscription_data[ 'start_date' ] = $subscription_details[ 'previous_active_enddate' ];
    }
    $subscription_data[ 'subscription_id' ] = "BENAJFREE";
    $subscription_data[ 'plan_name' ] = 'Free';
    $subscription_data[ 'plan_id' ] = 'dm8w';
    $subscription_data[ 'price' ] = '0';
    $subscription_data[ 'bill_start' ] = 'N/A';
    $subscription_data[ 'bill_end' ] = 'N/A';

    return $subscription_data;

}

///**
// * Function to get all details of subscription having free plan
// *
// * @param $subscription_details
// */
//function braintree_subscription_data( $subscription_details ) {
//
//    $subscription_data = get_subscription_details( $subscription_details[ 'subscription_id' ] );
//    return $subscription_data;
//
//}

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

//TODO : not used
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

/**
 * Function to delete a subscription in the db
 *
 * @param $subscription_id
 */
function delete_subscription( $subscription_id ) {
    global $wpdb;
    $wpdb->delete( 'subscription',array( 'subscription_id' => $subscription_id ) );
    
}

/**
 * Function to delete the subscription records for the domain in subscription table
 *
 * @param $domain_id
 */
function delete_subscription_for_domain( $domain_id ) {
    global $wpdb;

    // get the active subscription id for domain
    $subscription_id = get_subscription_id_for_domain( $domain_id );

    // cancel the active subscription on braintree
    if ( $subscription_id[ 'subscription_id' ] != "BENAJFREE" )
        cancel_subscription_in_braintree( $subscription_id[ 'subscription_id' ] );

    // cancel pending subscriptions if exists on braintree
    if ( isset( $subscription_id[ 'pending_subscription_id' ] ) )
        cancel_subscription_in_braintree( $subscription_id[ 'pending_subscription_id' ] );

    // delete all subscription records for the domain
    $wpdb->delete( 'subscription', array( 'domain_id' => $domain_id ) );
}

/**
 * Function to create a free pending subscription when pending plan is cancelled or
 * when user cancels free subscription
 * @param $domain_id
 */
function create_pending_free_subscription( $domain_id ) {

    global $wpdb;

    $table_name = 'subscription';

    $date_time = date( 'Y-m-d H:i:s' );

    $wpdb->insert( $table_name,
        array(
            'domain_id' => $domain_id,
            'subscription_id' => 'BENAJFREE',
            'datetime' => $date_time,
            'status' => 'pending'
        ) );
}

  
/**Functions for sending subscription related emails**/

/*  
* Send email when a new subscription is activated
*/
function subscription_active_email($user_name,$email_id, $subscription_id){

    global $aj_comm;

    $meta_data = array(
        'email_id' => $email_id,
        'user_name' => $user_name,
        'subscription_id' => $subscription_id
    );

    $comm_data = array(
        'component' => 'chatcat_subscriptions',
        'communication_type' => 'subscription_active'
    );

    $user = get_user_by( 'email', $email_id );

    $recipient_emails =  array(
                            array(
                                'user_id' => $user->ID,
                                'type' => 'email',
                                'value' => $user->user_email,
                                'status' => 'linedup'
                            )
                        );

    $aj_comm->create_communication($comm_data,$meta_data,$recipient_emails);

}

/*  
* Send email when a subscription is canceled
*/
function subscription_canceled_email($user_name,$email_id, $subscription_id){

    global $aj_comm;

    $meta_data = array(
        'email_id' => $email_id,
        'user_name' => $user_name,
        'subscription_id' => $subscription_id
    );

    $comm_data = array(
        'component' => 'chatcat_subscriptions',
        'communication_type' => 'subscription_canceled'
    );

    $user = get_user_by( 'email', $email_id );

    $recipient_emails =  array(
                            array(
                                'user_id' => $user->ID,
                                'type' => 'email',
                                'value' => $user->user_email,
                                'status' => 'linedup'
                            )
                        );

    $aj_comm->create_communication($comm_data,$meta_data,$recipient_emails);

}


function get_subscription_email_data($new_subscription_id){
    //new subscription
    $new_subscription_data = get_subscription_details($new_subscription_id);
    $new_plan_id = $new_subscription_data['plan_id'];
    $new_plan_data = get_plan_by_id( $new_plan_id ); 

    //Get domain id from subscription_id
    $domain_id = get_domain_for_bt_subscription($new_subscription_id);

    //domain details
    $domain_details = get_user_domain_details( $domain_id );
    $domain_name = $domain_details['domain'];
    
    $subscription_active_email_data =  array(
                                            'domain_name' => $domain_name,
                                            'new_plan' =>  $new_plan_data->name,
                                            'amount' => $new_plan_data->price,
                                            'plan_features' => $new_plan_data->description
                                            );
    return $subscription_active_email_data;
}

function get_domain_for_bt_subscription($subscription_id){

    global $wpdb;

    $domain_id= "";

    $query = "SELECT * FROM subscription WHERE subscription_id = '" . $subscription_id ."'";

    $query_result = $wpdb->get_row( $query, ARRAY_A );

    $domain_id = $query_result['domain_id'];

    return $domain_id;
}

