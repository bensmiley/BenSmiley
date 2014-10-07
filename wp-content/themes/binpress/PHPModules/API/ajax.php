<?php
/**
 * User: Mahima
 * Date: 6/30/14
 * Time: 4:02 PM
 */

/**
 * Function to generate the API key for a domain.
 *
 * @returns array containing API key and plan_id for domain
 */

/**
 * @api {get} http://chatcat.io/wp-admin/admin-ajax.php?action=get-api-key&domain=:domain Get API Key
 * @apiName GetAPIkey
 * @apiGroup API
 *
 * @apiParam {String} domain valid domain value
 *
 * @apiSuccess {Int} code Response code.(200)
 * @apiSuccess {String} api_key  API key requested
 * @apiSuccess {String} plan_id  Plan Id
 * @apiSuccess {Object} plan_details Plan details
 * @apiSuccess {String} plan_details.name Plan name
 * @apiSuccess {Int} plan_details.no_of_chatters Number of chatters
 * @apiSuccess {Boolean} plan_details.ads_enabled Ads enabled
 * @apiSuccess {Boolean} plan_details.single_sign_in Single sign in enabled
 * @apiSuccess {Boolean} plan_details.white_label White label enabled
 *
 * @apiError {Int} code Error code
 * @apiError {String} message Error message
 */
function ajax_get_api_key() {

    $domain = "";

    //PROCESS THE CLIENT REQUEST
    if ( isset( $_REQUEST[ 'domain' ] ) )
        $domain = $_REQUEST[ 'domain' ];

    if ( empty( $domain ) )
        echo wp_send_json( array( 'code' => 400, 'message' => 'Domain name not passed in the request' ) );

    $domain = refactor_domain($domain);

    //CHECK IF DOMAIN URL EXISTS IN DB ANG GET THE DOMAIN ID FOR THE URL IF EXISTS
    $domain_exists = check_domain_exists( $domain );

    if ( $domain_exists === false )
        echo wp_send_json( array( 'code' => 400, 'message' => 'Domain does not exists' ) );

    $domain_id = get_domain_ID($domain);

    // GET PLAN ID FOR THE DOMAIN

    //Get the active subscription for the domain
    $subscription_data = query_subscription_table( $domain_id );
    $current_active_subscription = $subscription_data[ 'subscription_id' ];

    //Get plan_id based on past_due status
    $plan_id = get_plan_id_for_api($current_active_subscription,$domain_id);

    $api_key = get_post_meta( $domain_id, 'api_key', true );


    $plan_details = get_plan($plan_id);

    //GENERATE API KEY FOR DOMAIN
    $response = array(
        'code' => 200,
        'api_key' => $api_key,
        'plan_id' => $plan_id,
        'plan_details' => $plan_details
    );

    echo wp_send_json( $response );
}

add_action( 'wp_ajax_nopriv_get-api-key', 'ajax_get_api_key' );
add_action( 'wp_ajax_get-api-key', 'ajax_get_api_key' );



/**
 * Function to get plan_id based on the past_due status of an active subscription
 * if the subscription is past_due, then api should return freeplan else it should return paid plan
 */
function get_plan_id_for_api($current_active_subscription,$domain_id){

    global $wpdb;
    $table = 'subscription';

    $susbscription_row = $wpdb->get_row("SELECT * FROM ".$table." WHERE subscription_id = '".$current_active_subscription."'");

    $past_due_status = $susbscription_row->past_due;
    
    //If the active subscription's past_due is 0, then fetch plan id from post meta
    $plan_id = get_post_meta( $domain_id, 'plan_id', true );

    //if the active subscription's past_due is 1, then plan id should be BT_FREEPLAN
    if ($past_due_status) {
        $plan_id = BT_FREEPLAN;
    }

    return $plan_id;

}

/**
*/
function get_plan($plan_id){

    global $bt_plans;

    if(!isset($bt_plans[$plan_id]))
        return array();

    return $bt_plans[$plan_id];
}

/**
 * Function to get the group details.
 * Accepts the API key for a domain
 * Returns a json array of group details containing group count, title and description
 */

/**
 * @api {get} http://chatcat.io/wp-admin/admin-ajax.php?action=get-group-details&api_key=:api_key Get group details
 * @apiName Get group details
 * @apiGroup API
 *
 * @apiParam {String} api_key API key
 *
 * @apiSuccess {Int} code Response code.(200)
 * @apiSuccess {Int} groups_count  API key requested
 * @apiSuccess {Array} details  array of groups data
 *
 * @apiError {Int} code Error code
 * @apiError {String} message Error message
 */
function ajax_get_group_details() {
    $api_key = "";

    //PROCESS THE CLIENT REQUEST
    if ( isset( $_REQUEST[ 'api_key' ] ) )
        $api_key = $_REQUEST[ 'api_key' ];

    if ( empty( $api_key ) ) {
        $response = array( 'code' => 400, 'message' => 'API key not passed with the request' );
        echo wp_send_json( $response );
    }

    // SANITIZE THE API KEY
    $api_key = sanitize_text_field( $api_key );

    //CHECK IF API EXSISTS FOR A DOMAIN IN THE DB
    $groups = array();
    $key_exists = false;
    $domain_id = 0;

    $args = array(
        'post_type' => 'domain',
        'meta_key' => 'api_key',
        'meta_value' => $api_key
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $key_exists = true;
        $query->the_post();
        $domain_id = get_the_ID();
        $groups = get_post_meta( $domain_id, 'groups', false );
    }
    wp_reset_postdata();

    if ( !$key_exists ) {
        $response = array( 'code' => 400, 'message' => 'API key does not exists for domain ' );
        echo wp_send_json( $response );
    }

    //CHECK IF THE GROUPS ARRAY RETURNED IS EMPTY
    if ( empty( $groups ) ) {
        $response = array(
            'code' => 200,
            'groups_count' => 0,
            'details' => array()
        );
        echo wp_send_json( $response );
    }

    //CALL THE GET GROUPS FUNCTION WHICH RETURNS THE LIST OF GROUPS FOR THE DOMAIN
    $groups = get_groups_for_domain( $domain_id );
    $group_count = count( $groups );

    $response = array(
        'code' => 200,
        'groups_count' => $group_count,
        'details' => $groups
    );
    echo wp_send_json( $response );
}

add_action( 'wp_ajax_get-group-details', 'ajax_get_group_details' );
add_action( 'wp_ajax_nopriv_get-group-details', 'ajax_get_group_details' );
