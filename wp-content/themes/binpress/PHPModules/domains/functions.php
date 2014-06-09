<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/9/14
 * Time: 4:01 PM
 */


//TODO: wtite rpoper comments
function get_current_user_domains( $current_user_id ) {

    global $wpdb;
    $sql = "SELECT ID FROM wp_posts WHERE post_type= %s AND post_author = %d ";

    $query = $wpdb->prepare( $sql, 'domain', $current_user_id );
    $post_ids = $wpdb->get_results( $query, ARRAY_A );

    foreach ( $post_ids as $post_id ):

     $domains_data[] = get_user_domain_details($post_id['ID']);

    endforeach;

    return $domains_data;


}

function create_user_domain( $domain_details ) {

    $post_array = array( 'post_author' => $domain_details[ 'user_id' ],
        'post_type' => 'domain',
        'post_title' => $domain_details[ 'domain_name' ] );

    $post_id = wp_insert_post( $post_array );

    if ( $post_id == 0 )
        return $post_id;

    update_post_meta( $post_id, 'domain_url', $domain_details[ 'domain_url' ] );
    $domain_data = get_user_domain_details( $post_id );
    return $domain_data;
}

function get_user_domain_details( $post_id ) {

    $domain_post_data = get_post( $post_id );

    if ( is_null( $domain_post_data ) )
        return $domain_post_data;

    $domain_post_meta_data = get_post_meta( $post_id );

    if ( empty( $domain_post_meta_data ) )
        return $domain_post_data;

    $domain_data = wp_parse_args( $domain_post_data, $domain_post_meta_data );
    return $domain_data;

}