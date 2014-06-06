<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/3/14
 * Time: 2:48 PM
 *
 * File Description :  Contains a list of ajax functions called from the user profile dashboard
 */

require "functions.php";

//TODO: write proper comment for function
/**
 * Function to get all details about the user currently logged in the system
 *
 *
 */
function ajax_read_user() {

    //Check if user logged in
    if ( is_user_logged_in() ) {

        $current_user_data = get_current_user_data();

        wp_send_json( array( 'code' => 'OK', 'data' => $current_user_data ) );
    } else {
        wp_send_json( array( 'msg' => 'User not logged in' ) );
    }

}

add_action( 'wp_ajax_read-user', 'ajax_read_user' );

/**
 * Function to update the user display details in the user profile page
 */
function ajax_update_user() {

    // get all the POST data of the user
    $userdata = $_POST;

    //unset the action field
    unset( $userdata[ 'action' ] );

    if ( update_user_display_details( $userdata ) ) {

        if(!empty($userdata[ 'user_pass' ]))
             wp_set_password( $userdata[ 'user_pass' ], $userdata[ 'ID' ] );

        wp_send_json( array( 'code' => 'OK', 'data' => $userdata ) );
    }

    wp_send_json( array( 'code' => 'OK', 'msg' => 'User profile update not successful' ) );
}

add_action( 'wp_ajax_update-user', 'ajax_update_user' );

/**
 * Function to return an array containing data about the current logged in user
 *
 * @return array of logged in user data
 */
function get_current_user_data() {

    $current_user = wp_get_current_user();

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

