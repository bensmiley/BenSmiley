<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/1/14
 * Time: 2:56 PM
 *
 * File Description : Contains a list of ajax functions called from the sign up form on the home page
 */

require "functions.php";

/**
 *
 * New User sign up ajax handler
 * This ajax action will accept a POST request.
 * This action will always be triggered by a non logged in user
 * hence, add_action("wp_ajax_nopriv_*")
 *
 * @return json success / or / failure
 */

function ajax_new_user_signup() {

    //check if it is a post request else return error
    if ( 'POST' !== $_SERVER [ 'REQUEST_METHOD' ] )
        wp_send_json( array( 'code' => 'ERROR', 'msg' => 'Invalid request' ) );

    //store all the POST data from the form in a variable
    $signup_form_data = $_POST;

    //pick the user specific fields from the POST data
    $user_data = pick_user_fields( $signup_form_data );

    $user_email = $user_data[ 'user_email' ];

    //check if the user email exists else return error
    $check_user_email = email_exists( $user_email );
    if ( $check_user_email )
        wp_send_json( array( 'code' => 'ERROR', 'msg' => '<div class="alert alert-error">
                  <button class="close" data-dismiss="alert"></button>Email ID already exists</div>' ) );

    // pass the user data to function create_new_user and capture return data
    $user_id = create_new_user( $user_data );

    // check if user created, on success returns user_id : on error (int)WP_Error
    if ( is_wp_error( $user_id ) )
        wp_send_json( array( 'code' => 'ERROR', 'msg' => '<div class="alert alert-error">
                  <button class="close" data-dismiss="alert"></button>User not created</div>' ) );

    //update the user status to 1, since user not activated
    update_user_status_in_db( $user_id );

    // generate the unique user activation key using user email
    $user_activation_key = generate_user_activation_key( $user_email );

    //insert the user activation key into the user record
    set_user_activation_key( $user_activation_key, $user_id );

    //insert user details in db for sending user-activation email through cron
    set_user_details_for_mail( $user_data, 'new-user-activation' );


    wp_send_json( array( 'code' => 'OK',
        'msg' => '<div class="alert alert-info">
                  <button class="close" data-dismiss="alert"></button>
                  An email has been sent to you. Please click on the link to confirm your account</div>' ) );

}

add_action( 'wp_ajax_nopriv_new-user-signup', 'ajax_new_user_signup' );