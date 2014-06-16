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
 * @param $current_user_id
 * @return array containing all the domains data registered under the user
 */
function get_current_user_domains( $current_user_id ) {

    global $wpdb;

    $domains = get_posts( array( 'post_type' => 'domain', 'author' => get_current_user_id() ) );

    $domains_data = array();

    foreach ( $domains as $domain ):

        $domains_data[ ] = get_user_domain_details( $domain->ID );

    endforeach;

    return $domains_data;

}

function create_user_domain( $domain_details ) {

    $post_array = array( 'post_author' => $domain_details[ 'user_id' ],
        'post_type' => 'domain',
        'post_title' => $domain_details[ 'post_title' ],
        'post_status' => 'publish' );

    $post_id = wp_insert_post( $post_array );

    if ( $post_id == 0 )
        return $post_id;

    // add the domain url as post meta for domain post
    update_post_meta( $post_id, 'domain_url', $domain_details[ 'domain_url' ] );

    // add the free plan as a term for domain post
    wp_set_post_terms( $post_id, 'Free','plan' );

    $domain_data = get_user_domain_details( $post_id );
    return $domain_data;
}

/**
 * Function to get the post and post meta data for each domain
 *
 * @param $post_id
 * @return array|null|WP_Post
 */
function get_user_domain_details( $post_id ) {

    $domain_post_data = get_post( $post_id );

    if ( is_null( $domain_post_data ) )
        return $domain_post_data;

    $domain_post_meta_data = get_post_meta( $post_id );

    if ( empty( $domain_post_meta_data ) )
        return $domain_post_data;

    $formatted_domain_meta_data = format_domain_post_meta_data( $domain_post_meta_data );

    $domain_data = wp_parse_args( $domain_post_data, $formatted_domain_meta_data );

    $plan = get_plan_name_id( $post_id );

    $domain_data['plan_id'] = $plan['id'];
    $domain_data['plan_name'] = $plan['name'];

    return $domain_data;

}

//TODO: make function proper for gruops
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

function update_domain_post( $domain_data ) {

    $domain_details = array(
        'ID' => $domain_data[ 'ID' ],
        'post_title' => $domain_data[ 'post_title' ] );

    $domain_post_id = wp_update_post( $domain_details );

    update_post_meta( $domain_post_id, 'domain_url', $domain_data[ 'domain_url' ] );

}