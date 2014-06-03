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

        $current_user = wp_get_current_user();

        wp_send_json( array( 'code' => 'OK', 'data' => $current_user->data ) );
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

        wp_set_password( $userdata[ 'user_pass' ], $userdata[ 'ID' ] );
        wp_send_json( array( 'code' => 'OK', 'data' => $userdata ) );
    }

    wp_send_json( array( 'code' => 'OK', 'msg' => 'User profile update not successful' ) );
}

add_action( 'wp_ajax_update-user', 'ajax_update_user' );

