<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/3/14
 * Time: 3:21 PM
 *
 * File Description :  Contains list of functions for user-profile
 */

/**
 * Function to update the user display details in DB
 *
 * @param array $user_data : contains all the user data\
 *
 * @return bool true if successful and bool false if unsuccessful
 */
function update_user_display_details( $user_data ) {

    // get the user id from array
    $user_id = $user_data['ID'];

    // prepare the array of values to update for the user
    $user_update_array = array( 'ID' => $user_id,
                                'display_name' => $user_data['display_name'],
                                'user_email' => $user_data['user_email'] );

    $user_id = wp_update_user( $user_update_array );

    if (is_wp_error( $user_id )) {

        return false;

    } else {

        //get the user first name and last name
        $user_first_name = strip_user_first_name( $user_data['display_name'] );
        $user_last_name = strip_user_last_name( $user_data['display_name'] );

        //update the user meta values for first name and last name
        update_user_meta( $user_id, 'first_name', $user_first_name );
        update_user_meta( $user_id, 'last_name', $user_last_name );

        return true;
    }
}