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
function ajax_get_api_key() {

    $domain_url = "";

    //PROCESS THE CLIENT REQUEST
    if ( isset( $_REQUEST[ 'url' ] ) )
        $domain_url = $_REQUEST[ 'url' ];

    if ( empty( $domain_url ) )
        echo wp_send_json( array( 'code' => 400, 'message' => 'Domain url not passed in the request' ) );


    // VALIDATE THE URL
    $validate_url = validate_domain_url( $domain_url );
    if ( $validate_url[ 'code' ] == "ERROR" )
        echo wp_send_json( array( 'code' => 400, 'message' => $validate_url[ 'msg' ] ) );

    $valid_url = $validate_url[ 'url' ];


    //CHECK IF DOMAIN URL EXISTS IN DB ANG GET THE DOMAIN ID FOR THE URL IF EXISTS
    $url_exists = check_url_exists( $valid_url );
    if ( $url_exists[ 'code' ] == "ERROR" )
        echo wp_send_json( array( 'code' => 400, 'message' => $url_exists[ 'msg' ] ) );

    $domain_id = $url_exists[ 'domain_id' ];

    // GET PLAN ID FOR THE DOMAIN
    $plan_id = get_post_meta( $domain_id, 'plan_id', true );


    //GENERATE API KEY FOR DOMAIN
    $salt = base64_encode( $valid_url . $plan_id );
    $key = sha1( $valid_url . time() . $plan_id . $salt );

    $response = array(
        'code' => 200,
        'api_key' => $key,
        'plan_id' => $plan_id
    );

    echo wp_send_json( $response );
}

add_action( 'wp_ajax_nopriv_get-api-key', 'ajax_get_api_key' );
add_action( 'wp_ajax_get-api-key', 'ajax_get_api_key' );

/**
 * Function to get the group details.
 * Accepts the API key for a domain
 * Returns a json array of group details containing group count, title and description
 */
function ajax_get_group_details() {
    $api_key = "";

    //PROCESS THE CLIENT REQUEST
    if ( isset( $_REQUEST[ 'api_key' ] ) )
        $api_key = $_REQUEST[ 'api_key' ];

    if ( empty( $api_key ) ) {
        $response = array( 'code' => 400, 'message' => 'API key not passed in the request' );
        echo wp_send_json( $response );
    }

    // SANITIZE THE API KEY
    $api_key = sanitize_text_field( $api_key );

    //CHECK IF API EXSISTS FOR A DOMAIN IN THE DB
    $groups = "";
    $key_exists = false;

    $args = array(
        'post_type' => 'domain',
        'meta_query' => array(
            array(
                'value' => $api_key
            )
        )
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
        $response = array( 'code' => 400, 'message' => 'API key does not exists for any url ' );
        echo wp_send_json( $response );
    }

    //CHECK IF THE GROUPS ARRAY RETURNED IS EMPTY
    if ( empty( $groups ) ) {
        $response = array(
            'code' => 200,
            'groups_count' => 0,
            'details' => NULL
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

add_action( 'wp_ajax_nopriv_get-group-details', 'ajax_get_group_details' );