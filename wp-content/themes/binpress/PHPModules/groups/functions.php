<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/18/14
 * Time: 3:14 PM
 *  * File Description :  Contains a list of functions for add/edit/list of groups for a domain
 */

/**
 * Function to create a group for a domain for the first time
 *
 * @param $group_data : posted from the form
 * @return serialized array of the group data
 */
function create_new_group_for_domain( $group_data ) {

    $group_details = array( 'group_name' => $group_data[ 'group_name' ],
        'group_description' => $group_data[ 'group_description' ] );

    $new_group = maybe_serialize( $group_details );

    update_post_meta( $group_data[ 'domain_id' ], 'groups', $new_group );

    return $new_group;
}

/**
 * Function to append groups to a domain, when they are already existing
 *
 * @param $group_data
 * @param $groups_meta
 * @return serialized array of all the groups data
 */
function  update_existing_groups( $group_data, $groups_meta ) {

    /**check if the array is multi-dimensional or flat,Used when
     * groups created for 2 time, the maybe_unserialize() returns
     * flat array and when groups created subsequently returns
     * multi dimensional array
    **/
    $existing_groups = maybe_unserialize( $groups_meta );

    if (count($existing_groups) == count($existing_groups, COUNT_RECURSIVE))
        $new_groups[] = $existing_groups;
    else
        $new_groups = $existing_groups;

    $new_group_details[ ] = array( 'group_name' => $group_data[ 'group_name' ],
    'group_description' => $group_data[ 'group_description' ] );

    $new_groups_meta = wp_parse_args( $new_groups, $new_group_details );

    $new_groups_meta_serialized = maybe_serialize( $new_groups_meta );

    update_post_meta( $group_data[ 'domain_id' ], 'groups', $new_groups_meta_serialized );

    return $new_groups_meta_serialized;

}