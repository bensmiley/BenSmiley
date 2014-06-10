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
        'post_title' => $domain_details[ 'post_title' ] );

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

    $formatted_domain_meta_data = format_domain_post_meta_data($domain_post_meta_data);

    $domain_data = wp_parse_args( $domain_post_data, $formatted_domain_meta_data );
    return $domain_data;

}
//TODO: write proper comment and make function proper for gruops
function format_domain_post_meta_data($domain_post_meta_data){
    foreach($domain_post_meta_data as $key => $value){

       $formatted_array[$key]= $value[0];

    }
    return $formatted_array;
}

function update_domain_post($domain_data){

    $domain_details= array(
                    'ID'           => $domain_data['ID'],
                    'post_title' => $domain_data['post_title']);

    $domain_post_id = wp_update_post($domain_details);

    update_post_meta($domain_post_id,'domain_url',$domain_data['domain_url']);

}