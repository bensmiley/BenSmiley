<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/3/14
 * Time: 2:48 PM
 *
 * File Description :  Contains a list of ajax functions called from the user profile dashboard
 */

//TODO: write proper comment for function
/**
 * Function to get all details about the user currently logged in the system
 *
 *
 */
function ajax_read_user() {

    //Check if user logged in
    if(is_user_logged_in()){

        $current_user = wp_get_current_user();
    }
    wp_send_json( array( 'code' => 'OK','data'=>$current_user->data) );
}

add_action( 'wp_ajax_read-user', 'ajax_read_user' );

