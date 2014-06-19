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

    $id = intval( rand() );

    $group_details = array( 'ID' => $id, 'group_name' => $group_data[ 'group_name' ],
        'group_description' => $group_data[ 'group_description' ] );

    $new_group = maybe_serialize( $group_details );

    update_post_meta( $group_data[ 'domain_id' ], 'groups', $new_group );

    return $group_details;
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

    if ( count( $existing_groups ) == count( $existing_groups, COUNT_RECURSIVE ) )
        $new_groups[ ] = $existing_groups;
    else
        $new_groups = $existing_groups;

    $id = intval( rand() );

    $new_group_details[ ] = array( 'ID' => $id, 'group_name' => $group_data[ 'group_name' ],
        'group_description' => $group_data[ 'group_description' ] );

    $new_groups_meta = wp_parse_args( $new_groups, $new_group_details );

    $new_groups_meta_serialized = maybe_serialize( $new_groups_meta );

    update_post_meta( $group_data[ 'domain_id' ], 'groups', $new_groups_meta_serialized );

    return $new_groups_meta;

}

/**
 * Function to fetch all the groups associated with a domain
 *
 * @param $domain_id
 * @return empty array if no groups found | array of groups data
 */
function get_groups_for_domain( $domain_id ) {

    $groups = get_post_meta( $domain_id, 'groups' );

    // if no groups found
    if ( empty( $groups ) )
        return array();

    foreach ( $groups as $group ):
        $groups_array = maybe_unserialize( $group );
    endforeach;

    return $groups_array;

}

/**
 * Function to update the group deatils for a domain
 *
 * @param $group_data
 * @return mixed
 */
function update_group_for_domain( $group_data ) {
    $groups = get_groups_for_domain( $group_data[ 'domain_id' ] );

    foreach ( $groups as $key => $group ):
        if ( $group[ 'ID' ] == $group_data[ 'ID' ] ) {

            $update_group[ $key ][ 'ID' ] = intval( $group_data[ 'ID' ] );
            $update_group[ $key ][ 'group_name' ] = $group_data[ 'group_name' ];
            $update_group[ $key ][ 'group_description' ] = $group_data[ 'group_description' ];

        } else {

            $update_group[ $key ] = $group;
        }

    endforeach;
//    $new_groups_meta = wp_parse_args( $update_groups, $update_group );

    $update_group_serialized = maybe_serialize( $update_group );

    update_post_meta( $group_data[ 'domain_id' ], 'groups', $update_group_serialized );

    return $update_group;
}