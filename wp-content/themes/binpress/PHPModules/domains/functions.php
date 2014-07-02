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

//    $domain_url = validate_domain_url( $domain_details[ 'domain_url' ] );
//
//    if ( $domain_url[ 'code' ] == "ERROR" )
//        wp_send_json( array( 'code' => 'ERROR', 'msg' => $domain_url[ 'msg' ] ) );

    $post_id = wp_insert_post( $post_array );

    if ( $post_id == 0 )
        return $post_id;

    // add the domain url as post meta for domain post
    update_post_meta( $post_id, 'domain_url', $domain_details[ 'domain_url' ] );
    update_post_meta( $post_id, 'plan_id', 'dm8w' );

    // add the free plan as a term for domain post
    wp_set_post_terms( $post_id, 'Free', 'plan' );

    // create a free subscription for the domain
    create_free_subscription( $post_id );

    return $post_id;
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
 * The function formats the url into : http://www.example.com
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
 * @return array
 */
function validate_domain_url( $domain_url ) {
    // SANITIZE THE URL
    $domain_url = esc_url_raw( $domain_url );

    if ( empty( $domain_url ) )
        return array( 'code' => "ERROR", 'msg' => 'Domain url passed contains incorrect protocol' );

    //CHECK IF DOMAIN URL VALID
    $regex = "@(https|http)://(-\.)?([^\s/?\.#,!$%^&*()-]+\.?)+(/[^\s]*)?$@iS";

    if ( !preg_match( $regex, $domain_url ) )
        return array( 'code' => "ERROR", 'msg' => 'Invalid domain url passed ' );

    //BUILD A VALID URL
    $protocol = parse_url( $domain_url, PHP_URL_SCHEME );
    $host = parse_url( $domain_url, PHP_URL_HOST );

    //CHECK IF HOST NAME HAS WWW
    if ( strripos( $host, "www" ) === FALSE ) {
        $host = "www." . $host;
    }

    $url = $protocol . "://" . $host;

    return array( 'code' => 'OK', 'url' => $url );
}