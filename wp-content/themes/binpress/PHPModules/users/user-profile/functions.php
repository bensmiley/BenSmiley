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
 * @param array $userdata : contains all the user data
 *
 * @return bool true if successful and bool false if unsuccessful
 */
function update_user_display_details( $user_data ) {

    // get the user id from array
    $user_id = $user_data[ 'ID' ];

    // prepare the array of values to update the user
    $user_update_array = array( 'ID' => $user_id,
        'display_name' => $user_data[ 'display_name' ],
        'user_email' => $user_data[ 'user_email' ] );

    $user_id = wp_update_user( $user_update_array );

    if ( is_wp_error( $user_id ) ) {

        return false;

    } else {

        update_user_display_meta( $user_id, $user_data );

        return true;
    }
}

/**
 * Function to update the user meta details for user profile page
 *
 * @param $user_id
 * @param $userdata
 */
function update_user_display_meta( $user_id, $userdata ) {

    //get the user first name and last name
    $user_first_name = strip_user_first_name( $userdata[ 'display_name' ] );
    $user_last_name = strip_user_last_name( $userdata[ 'display_name' ] );

    //update the user meta values for first name and last name
    update_user_meta( $user_id, 'first_name', $user_first_name );
    update_user_meta( $user_id, 'last_name', $user_last_name );

    // update the user profile image only if photo ID passed
    if ( !empty( $userdata[ 'user_photo_id' ] ) )
        update_user_meta( $user_id, 'user_photo_id', $userdata[ 'user_photo_id' ] );
}

/**
 * Function to return an array containing data about the current logged in user
 *
 * @return array of logged in user data
 */
function get_current_user_data() {

    $user_id = get_current_user_id();

    $current_user = get_userdata( $user_id );

    $user_photo_url = "http://1.gravatar.com/avatar/5ba2411c7d1e867264c0dcec5a160a46?s=64&d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D64&r=G";

    // get the display image of the logged in user
    $user_photo_id = get_user_meta( $current_user->data->ID, 'user_photo_id', true );

    if ( !empty( $user_photo_id ) )
        $user_photo_url = wp_get_attachment_thumb_url( $user_photo_id );


    return array( 'ID' => $current_user->data->ID,
        'user_login' => $current_user->data->user_login,
        'user_email' => $current_user->data->user_email,
        'display_name' => $current_user->data->display_name,
        'user_photo' => $user_photo_url,
        'role' => $current_user->roles,
        'capabilities' => $current_user->allcaps );

}