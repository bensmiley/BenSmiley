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

    $domain = $domain_details[ 'domain' ];

    // check domain url for uniqueness
    $domain_exists = check_domain_unique( $domain );

    if ( $domain_exists === true )
        return array( 'code' => "ERROR", 'msg' => 'Domain already registered' );

    // insert domain details
    $post_id = wp_insert_post( $post_array );

    if ( $post_id == 0 )
        return array( 'code' => "ERROR", 'msg' => "Could not create domain" );

    $domain = refactor_domain($domain);

    // add the domain url as post meta for domain post
    update_post_meta( $post_id, 'domain', $domain );
    update_post_meta( $post_id, 'plan_id', BT_FREEPLAN );

    // add the free plan as a term for domain post
    wp_set_post_terms( $post_id, 'Free', 'plan' );

    // create a free subscription for the domain
    create_free_subscription( $post_id );

    //generate api key for the domain by calling the api key generation API
    $key = get_key_from_API( $domain );

    //update the domain meta with the api key
    update_post_meta( $post_id, 'api_key', $key );

    return array( 'code' => "OK", 'domain_id' => $post_id );
}


/**
*/
function refactor_domain($domain){
    //REMOVE HTTP IF PRESENT
    
    //CHECK IF HOST NAME HAS WWW AND REMOVE IT
    if ( strripos( $domain, "www" ) === 0 ) {
        $domain = str_ireplace( "www.", "", $domain );
    }

    return $domain;
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

    $domain = refactor_domain($domain_data[ 'domain' ]);

    update_post_meta( $domain_post_id, 'domain', $domain );

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
function check_domain_unique( $domain ) {
    // intialize variable to false
    $domain_exists = false;

    // since the post id is not present,use wp-query to query the post meta table
    // and check if the domain url is exists
    $args = array(
        'post_type' => 'domain',
        'meta_key' => 'domain',
        'meta_value' => $domain
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() )
        $domain_exists = true;

    wp_reset_postdata();

    return $domain_exists;

}

function get_domain_ID($domain){

    // intialize variable to false
    $domain_exists = false;

    // since the post id is not present,use wp-query to query the post meta table
    // and check if the domain url is exists
    $args = array(
        'post_type' => 'domain',
        'meta_key' => 'domain',
        'meta_value' => $domain
    );

    $query = new WP_Query( $args );

    $domain_ID = 0;
    while($query->have_posts()): $query->the_post();
        $domain_ID = get_the_ID();
    endwhile;

    wp_reset_postdata();

    return $domain_ID;

}

/**
 * Function to check if url exists for a domain and if true return the domain id
 * @param $url
 * @return array
 */
function check_domain_exists( $domain ) {

    return check_domain_unique( $domain );

}

function get_key_from_API( $domain ) {

    $salt = base64_encode( $domain );
    $key = sha1( $domain . time() . $salt );

    return $key;
}
