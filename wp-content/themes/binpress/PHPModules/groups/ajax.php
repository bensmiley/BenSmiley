<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/10/14
 * Time: 3:05 PM
 *
 * File Description :  Contains a list of ajax functions called from the dashboard for add/edit/list
 *                      of groups
 */
require "functions.php";

/**
 * Function to add a new group for the domain
 */
function ajax_create_domain_group() {

    $group_data = $_POST;

    $new_group = '';

    //unset action
    unset( $group_data[ 'action' ] );

    // check the group meta for the domain, if groups exists
    $groups_meta = get_post_meta( $group_data[ 'domain_id' ], 'groups', true );

    //if group created for first time
    if ( empty( $groups_meta ) )
        $new_group = create_new_group_for_domain( $group_data );
    else
        $new_group = update_existing_groups( $group_data, $groups_meta );

    wp_send_json( array( 'code' => 'OK', 'data' => $new_group ) );
}

add_action( 'wp_ajax_create-domain-group', 'ajax_create_domain_group' );