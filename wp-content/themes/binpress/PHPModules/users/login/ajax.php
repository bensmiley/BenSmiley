<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/1/14
 * Time: 10:01 PM
 *
 *  File Description :  Contains a list of ajax functions called from the login form on the home page
 */

/***
 *
 * Function to log in user into the site
 * */
function ajax_user_login() {

    $site_url = get_site_url();

    // check if a user is already logged in
    if ( is_user_logged_in() ) {

        $message =  "User already logged in";

        success_message( $message, $site_url );

    }

    // clean the username and password
    $user_email = trim( $_POST[ 'user_email' ] );
    $user_pass = trim( $_POST[ 'user_pass' ] );

    $credentials = array();

    //set the login credentials
    $credentials[ 'user_login' ] = $user_email;
    $credentials[ 'user_password' ] = $user_pass;

    // check if the user exists for the email
    $user = get_user_by( 'email', $user_email );

    if ( $user ) {

        //login the user using WP function
        $user_login = wp_signon( $credentials );

        if ( is_wp_error( $user_login ) ) {
            $message = "The Email Id/ Password doesnt seem right.
                    Check if your caps is on and try again.";

            error_message( $message );

        } else {

            $message = "Login Success";

            success_message( $message, $site_url );
        }
    } else {
        $message = "No user exists with the email id.";
        error_message( $message );
    }
}

add_action( 'wp_ajax_user-login', 'ajax_user_login' );
add_action( 'wp_ajax_nopriv_user-login', 'ajax_user_login' );

/**
 * Function to return the error message as json
 *
 * @param $message
 */
function error_message( $message ) {

    $msg = '<div class="alert alert-error">
                <button class="close" data-dismiss="alert"></button>' .
        $message . '</div>';

    $response = array( 'code' => "ERROR", 'msg' => $msg );

    wp_send_json( $response );
}

/**
 * Function to return the success message as json
 *
 * @param $message
 */
function success_message( $message, $site_url ) {

    $response = array( "code" => "OK", 'site_url' => $site_url,
        'msg' => '<div class="alert alert-success">
                  <button class="close" data-dismiss="alert"></button>' .
            $message . '</div>' );

    wp_send_json( $response );
}

/**
 * Function to logout a user from the site
 */
function ajax_user_logout() {

    wp_logout();

    wp_send_json( array( 'code' => 'OK', 'redirect_url' => home_url() ) );

}

add_action( 'wp_ajax_user-logout', 'ajax_user_logout' );

