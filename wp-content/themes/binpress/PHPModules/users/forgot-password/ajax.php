<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/1/14
 * Time: 10:21 PM
 *
 * File Description :  Contains a list of ajax functions called from the forgot password form on the home page
 */
require "functions.php";

/**
 * Function to reset the user password. Triggered when user click on forgot password
 * link. Takes the user email as input parameter through $_POST
 *
 */
function ajax_reset_user_password() {

    // get clean user email
    $user_email = trim( $_POST[ 'user_email' ] );

    // get the user registered with the email id
    $user_data = get_user_by( 'email', $user_email );

    //check if a user with the email exists
    if ( !$user_data ) {

        $msg = "Email does not exists";

        wp_send_json( array( 'code' => 'ERROR', 'msg' => $msg ) );
    }

    //take the user credentials  from the user object
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    //check if password reset allowed for user
    $allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

    if ( !$allow ) {

        $msg = "Password reset is not allowed for this user";
        wp_send_json( array( 'code' => 'ERROR', 'msg' => $msg ) );

    } else if ( is_wp_error( $allow ) ) {

        wp_send_json( array( 'code' => 'ERROR', 'msg' => $allow ) );

    }
    //get the new user activation key
    $user_activation_key = generate_user_activation_key( $user_email );

    //insert the user activation key in db for the user
    update_new_user_activation_key_db( $user_activation_key, $user_login );

    //insert user details in db for sending password reset email through cron
    set_user_details_for_mail( array( 'user_email' => $user_email ), 'user-password-reset' );

    $msg = "Check mail for resetting your password";
    wp_send_json( array( 'code' => 'OK', 'msg' => $msg ) );

}

add_action( 'wp_ajax_nopriv_reset-user-password', 'ajax_reset_user_password' );

/**
 * Function to validate the password reset url, checks for the following:
 *
 * 1)the get parameters are set,
 * 2)the user exists for the email passed in the url
 * 3)if user exists, check if the user status set to 0
 * 4)check if user activation key is valid
 *
 * @param $get_parameters
 * @param $form_action
 * @return array of error msg, if not validated and
 * on successful validation returns array containing user email
 *
 */
function validate_reset_password_url( $get_parameters, $form_action ) {

    $get_param_check = check_get_parameters( $get_parameters, $form_action );
    if ( !$get_param_check[ 'code' ] )
        return $get_param_check;

    $user_exists_check = check_user_exists( $get_parameters[ 'login' ] );
    if ( !$user_exists_check[ 'code' ] )
        return $user_exists_check;

    // get user data
    $user_data = get_user_data( $get_parameters[ 'login' ] );

    $user_status_check = check_user_status_password_reset( $user_data );
    if ( !$user_status_check[ 'code' ] )
        return $user_status_check;

    $activation_key_check = validate_activation_key( $user_data );
    if ( !$activation_key_check[ 'code' ] )
        return $activation_key_check;

    return array( "code" => true, "user_data_obj" => $user_data );
}

/**
 * Function to reset the user password. Triggered when user click on forgot password
 *
 * link. Takes the user email as input parameter through $_POST
 *
 */
function ajax_change_password() {

    $user_data = get_user_data( $_POST[ 'user_email' ] );

    wp_set_password( $_POST[ 'user_pass' ], $user_data->ID );

    $msg = "Password change successful";

    wp_send_json( array( 'code' => 'OK', 'msg' => $msg ) );

}

// FIXME: wp_ajax_nopriv is for non logged in users. How can a non logged in user change password?
add_action( 'wp_ajax_nopriv_change-password', 'ajax_change_password' );