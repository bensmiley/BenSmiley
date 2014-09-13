<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/9/14
 * Time: 4:01 PM
 *
 * File Description :  Contains list of functions for user-domains
 */


/**
 * Function to get all the domains registered under the current user
 *
 * @return array containing list of all domains data registered under the user
 */
function get_current_user_domains() {

    $domains = get_posts( array( 'post_type' => 'domain', 'author' => get_current_user_id() ) );

    $domains_data = array();

    foreach ( $domains as $domain ):

        $domains_data[ ] = get_user_domain_details( $domain->ID );

    endforeach;

    return $domains_data;

}

/**
 * Function to add a new domain by the user
 *
 * @param $domain_details
 * @return int $domainId |WP_Error
 */
function create_user_domain( $domain_details ) {

    $post_array = array( 'post_author' => $domain_details[ 'user_id' ],
        'post_type' => 'domain',
        'post_title' => $domain_details[ 'post_title' ],
        'post_status' => 'publish' );

    // validate the domain url
    $domain_url_check = validate_domain_url( $domain_details[ 'domain_url' ] );

    if ( $domain_url_check[ 'code' ] == "ERROR" )
        return array( 'code' => "ERROR", 'msg' => $domain_url_check[ 'msg' ] );

    $formatted_url = $domain_url_check[ 'url' ];

    // check domain url for uniqueness
    $domain_url_unique = check_url_unique( $formatted_url );

    if ( $domain_url_unique[ 'code' ] == "ERROR" )
        return array( 'code' => "ERROR", 'msg' => $domain_url_unique[ 'msg' ] );

    // insert domain details
    $post_id = wp_insert_post( $post_array );

    if ( $post_id == 0 )
        return array( 'code' => "ERROR", 'msg' => "Could not create domain" );

    // add the domain url as post meta for domain post
    update_post_meta( $post_id, 'domain_url', $formatted_url );
    update_post_meta( $post_id, 'plan_id', 'dm8w' );

    // add the free plan as a term for domain post
    wp_set_post_terms( $post_id, 'Free', 'plan' );

    // create a free subscription for the domain
    create_free_subscription( $post_id );

    //generate api key for the domain by calling the api key generation API
    $key = get_key_from_API( $formatted_url );

    //update the domain meta with the api key
    update_post_meta( $post_id, 'api_key', $key );

    return array( 'code' => "OK", 'domain_id' => $post_id );
}

/**
 * Function to get the post and post meta data and term details for each domain
 *
 * @param $domain_id
 * @return array|null|WP_Post
 */
function get_user_domain_details( $domain_id ) {

    $domain_post_data = get_post( $domain_id );

    if ( is_null( $domain_post_data ) )
        return $domain_post_data;

    // format the domain registered date
    $domain_post_data->post_date = date( 'd/m/Y', strtotime( $domain_post_data->post_date ) );

    $domain_post_meta_data = get_post_meta( $domain_id );

    if ( empty( $domain_post_meta_data ) )
        return $domain_post_data;

    $formatted_domain_meta_data = format_domain_post_meta_data( $domain_post_meta_data );

    $domain_data = wp_parse_args( $domain_post_data, $formatted_domain_meta_data );

    // set current subscription for domain
    $subscription_data = query_subscription_table( $domain_id );
    $domain_data[ 'subscription_id' ] = $subscription_data[ 'subscription_id' ];

    // get the plan details for the current domain
    $plan_data = wp_get_post_terms( $domain_id, 'plan' );
    $domain_data[ 'plan_name' ] = $plan_data[ 0 ]->name;

    return $domain_data;

}

/**
 * Function to format the domain post meta data in proper key value pair
 *
 * Post meta data for domains:
 * domain_url
 *
 * @param $domain_post_meta_data
 *
 * @return formatted array of domain post meta data
 */
function format_domain_post_meta_data( $domain_post_meta_data ) {

    foreach ( $domain_post_meta_data as $key => $value ) {

        $formatted_array[ $key ] = $value[ 0 ];
    }

    return $formatted_array;
}

/** Function to update the domain name and url
 *
 * @param $domain_data
 */
function update_domain_post( $domain_data ) {

    $domain_details = array(
        'ID' => $domain_data[ 'ID' ],
        'post_title' => $domain_data[ 'post_title' ] );

    $domain_post_id = wp_update_post( $domain_details );

    update_post_meta( $domain_post_id, 'domain_url', $domain_data[ 'domain_url' ] );

}


/**
 * Function to delete a user domain
 *
 * @param $domain_id
 */
function delete_domain( $domain_id ) {

    //deletes the post,post meta and taxonomy terms
    wp_delete_post( $domain_id, true );

    // delete the subscription records for the domain in subscription table and braintree
    delete_subscription_for_domain( $domain_id );

}

/**
 * Function to check and clean the url before inserting in table.
 * The function formats the url into : abc.com
 *
 * accepts the domain url in any format as such:
 * abc.com
 * www.abc.com
 * example.abc.com
 * http://abc.com
 *
 * and converts it into proper format:
 *
 * @param $domain_url
 * @return array of error msg or formatted domain url on success
 */
function validate_domain_url( $domain_url ) {
    // SANITIZE THE URL
    $domain_url = esc_url_raw( $domain_url );

    if ( empty( $domain_url ) )
        return array( 'code' => "ERROR", 'msg' => 'Domain url contains incorrect protocol' );

    //CHECK IF DOMAIN URL VALID
    $regex = "@(https|http)://(-\.)?([^\s/?\.#,!$%^&*()-]+\.?)+(/[^\s]*)?$@iS";

    if ( !preg_match( $regex, $domain_url ) )
        return array( 'code' => "ERROR", 'msg' => 'Invalid domain url passed ' );

    //REMOVE HTTP IF PRESENT
    $host = parse_url( $domain_url, PHP_URL_HOST );

    //CHECK IF HOST NAME HAS WWW AND REMOVE IT
    if ( strripos( $host, "www" ) === 0 ) {
        $host = str_ireplace( "www.", " ", $host );
    }

    return array( 'code' => 'OK', 'url' => $host );
}

/**
 * Function to check if the passed url already exists in the db
 *
 * @param $url
 * @return array of error and success msg
 */
function check_url_unique( $url ) {
    // intialize variable to false
    $url_exists = false;

    // since the post id is not present,use wp-query to query the post meta table
    // and check if the domain url is exists
    $args = array(
        'post_type' => 'domain',
        'meta_query' => array(
            array(
                'value' => $url
            )
        )
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() )
        $url_exists = true;

    wp_reset_postdata();

    if ( $url_exists )
        return array( 'code' => "ERROR", 'msg' => 'Domain already exists' );
    else
        return array( 'code' => "OK" );

}

/**
 * Function to check if url exists for a domain and if true return the domain id
 * @param $url
 * @return array
 */
function check_url_exists( $url ) {
    $domain_id = " ";

    $args = array(
        'post_type' => 'domain',
        'meta_query' => array(
            array(
                'value' => $url
            )
        )
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $query->the_post();
        $domain_id = get_the_ID();
    }
    wp_reset_postdata();

    if ( (int) $domain_id  === 0 )
        return array( 'code' => 'ERROR', 'msg' => 'domain does not exists' );
    else
        return array( 'code' => 'OK', 'domain_id' => $domain_id );

}

function get_key_from_API( $url ) {

    $key_generation_url = admin_url( "admin-ajax.php" ) . "?action=get-api-key&url=" . $url;
    $api_response = file_get_contents( $key_generation_url );
    $api_key = json_decode( $api_response, true );
    return $api_key['api_key'];
}