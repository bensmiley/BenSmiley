<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/10/14
 * Time: 3:05 PM
 *
 * File Description :  Contains a list of ajax functions called from the dashboard for
 *                     add/edit/list/delete of groups
 */
require "functions.php";

/**
 * Function to add a new group for the domain
 */
function ajax_create_domain_group() {

    $group_data = $_POST;

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

/**
 * Function to fetch all the groups for a domain
 */
function ajax_fetch_groups() {

    $domain_id = $_GET[ 'domain_id' ];

    $groups = get_groups_for_domain( $domain_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $groups ) );
}

add_action( 'wp_ajax_fetch-groups', 'ajax_fetch_groups' );

/**
 * Function to update the group details of a domain
 */
function ajax_update_domain_group() {

    $group_data = $_POST;

    $updated_group_details = update_group_for_domain( $group_data );

    wp_send_json( array( 'code' => 'OK', 'data' => $updated_group_details ) );
}

add_action( 'wp_ajax_update-domain-group', 'ajax_update_domain_group' );

/**
 * Function to delete a group
 */
function ajax_delete_domain_group() {

    $group_data = $_POST;

    $updated_group_details = delete_group_for_domain( $group_data );

    wp_send_json( array( 'code' => 'OK', 'data' => $updated_group_details ) );
}

add_action( 'wp_ajax_delete-domain-group', 'ajax_delete_domain_group' );