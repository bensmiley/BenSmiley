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
    $user_email = trim( $_POST['user_email'] );

    // get the user registered with the email id
    $user_data = get_user_by( 'email', $user_email );

    //check if a user with the email exists
    if (!$user_data) {

        $msg = '<div class="alert alert-error">
                <button class="close" data-dismiss="alert"></button>This Email Id does not exists.</div>';

        wp_send_json( array( 'code' => 'ERROR', 'msg' => $msg ) );
    }

    //take the user credentials  from the user object
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    //check if password reset allowed for user
    $allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

    if (!$allow) {

        $msg = "Password reset is not allowed for this user";
        wp_send_json( array( 'code' => 'ERROR', 'msg' => $msg ) );

    } else if (is_wp_error( $allow )) {

        wp_send_json( array( 'code' => 'ERROR', 'msg' => $allow ) );

    }
    //get the hashed user activation key
    $hashed_user_activation_key = generate_hashed_user_activation_key();

    //insert the hashed user activation key in db for the user
    update_new_user_activation_key_db( $hashed_user_activation_key, $user_login );
    //insert user details in db for sending password reset email through cron
    set_user_details_for_mail( array( 'user_email' => $user_email ), 'user-password-reset' );

    $msg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"></button>
            Kindly check your email for resetting your password</div>';
    wp_send_json( array( 'code' => 'OK', 'msg' => $msg ) );

}

add_action( 'wp_ajax_nopriv_reset-user-password', 'ajax_reset_user_password' );

//TODO : remove the hash password and use normal passord
function validate_reset_password_url( $get_parameters, $form_action ) {

    $get_param_check = check_get_parameters( $get_parameters, $form_action );
    if (!$get_param_check['code'])
        return $get_param_check;

    $user_exists_check = check_user_exists( $get_parameters['login'] );
    if (!$user_exists_check['code'])
        return $user_exists_check;

    $user_status_check = check_user_status_password_reset( $user_exists_check['user_data'] );
    if (!$user_status_check['code'])
        return $user_status_check;

//    $activation_duration_check = validate_reset_password_key($get_parameters['key'],$user_exists_check['user_data']);
//    if (!$activation_duration_check['code'])
//        return $activation_duration_check;

    return array( "code" => true, "user_data_obj" => $user_exists_check['user_data'] );


}

/**
 * Function to reset the user password. Triggered when user click on forgot password
 * link. Takes the user email as input parameter through $_POST
 *
 */
function ajax_change_password() {

    $user_data = get_user_data( $_POST['user_email'] );

    reset_password( $user_data, $_POST['user_pass'] );

    $msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Password change successful</div>';
    wp_send_json( array( 'code' => 'OK', 'msg' => $msg ) );

}
// FIXME: wp_ajax_nopriv is for non logged in users. How can a non logged in user change password?
add_action( 'wp_ajax_nopriv_change-password', 'ajax_change_password' );