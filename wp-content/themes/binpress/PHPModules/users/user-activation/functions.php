<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/1/14
 * Time: 4:45 PM
 *
 * File Description :  Contains list of functions for user-activation module
 */

/**
 * Function to validate the activation url, checks for the following:
 *
 * 1)the get parameters are set,
 * 2)the user exists for the email passed in the url
 * 3)if user exists, check if the user status set to 1
 * 4)check if user registeration date is greater than 3 days.
 * 5)check if user activation key is valid
 *
 * @param $get_parameters
 * @param $form_action
 * @return array of error msg, if not validated and
 * on successful validation returns array containing user email
 *
 */
function validate_activation_url( $get_parameters, $form_action ) {

    $get_param_check = check_get_parameters( $get_parameters, $form_action );
    if (!$get_param_check['code'])
        return $get_param_check;

    $user_exists_check = check_user_exists( $get_parameters['login'] );
    if (!$user_exists_check['code'])
        return $user_exists_check;

    // get the user data
    $user_data = get_user_data( $get_parameters['login'] );

    $user_status_check = check_user_status( $user_data );
    if (!$user_status_check['code'])
        return $user_status_check;

    $activation_duration_check = check_user_activation_duration( $user_data );
    if (!$activation_duration_check['code'])
        return $activation_duration_check;

    $activation_key_check = validate_activation_key( $user_data );
    if (!$activation_key_check['code'])
        return $activation_key_check;

    return array( "code" => true, "user_data_obj" => $user_data );
}

/**
 * Function to check if the form action and the GET parameters of the URL are valid
 *
 * @param $get_parameters
 * @param $form_action
 * @return array containing the error or success message
 */
function check_get_parameters( $get_parameters, $form_action ) {

    // Check if the form action isset and matches with the form action of the page
    if (isset($get_parameters['action']) && $get_parameters['action'] == $form_action) {

        //Check if the key and login parameters are set in the URL
        if (isset($get_parameters['key']) && isset($get_parameters['login'])) {
            $success_msg = array( "code" => true );
            return $success_msg;
        } else {
            $error_msg = array( "code" => false, "msg" => "Broken Link" );
            return $error_msg;
        }
    } else {
        $error_msg = array( "code" => false, "msg" => "Broken Link" );
        return $error_msg;
    }

}

/**
 * Function to check if the user exist in the db
 *
 * @param $user_email
 * @return array containing the error or success message
 */

function check_user_exists( $user_email ) {

    $user_data = email_exists( $user_email );

    if ($user_data == true) {
        $success_msg = array( "code" => true, 'user_data' => $user_data );
        return $success_msg;

    } else {
        $error_msg = array( "code" => false, "msg" => "No such user exists" );
        return $error_msg;
    }

}

/**
 * Function to check the user status in the db
 *
 * @param $user_data
 * @return array containing the error or success message
 */
function check_user_status( $user_data ) {

    if ($user_data->user_status == 0) {

        $error_msg = array( "code" => false, "msg" => "Link expired and user already activated" );
        return $error_msg;

    } else {
        $success_msg = array( "code" => true );
        return $success_msg;
    }


}

/**
 * Function to check the user activation_duration in the db
 *
 * If user activation duration is more than 3 days, then user is disabled
 *
 * @param $user_data
 * @return array containing the error or success message
 */
function check_user_activation_duration( $user_data ) {

    if (strtotime( $user_data->user_registered ) + 3 * 24 * 60 * 60 < time()) {

        $error_msg = array( "code" => false, "msg" => "Activation time of 3 days expired" );
        return $error_msg;

    } else {

        $success_msg = array( "code" => true );
        return $success_msg;
    }
}

/**
 * Function to check if the user activation key is valid and
 *
 * matches the db activation key record for the user
 *
 * @param $user_data
 * @return array containing the error or success message
 */
function validate_activation_key( $user_data ) {

    global $wpdb;

    $table_name = $wpdb->users;

    $query = "SELECT count(*) as user_count FROM $table_name WHERE user_login = %s AND user_activation_key = '%s'";

    $user = $wpdb->get_results( $wpdb->prepare( $query, $user_data->user_login, $user_data->user_activation_key ), ARRAY_A );

    $count = $user[0]['user_count'];

    if ($count == 0)
        return array( "code" => false, "msg" => "Invalid activation key" );

    return array( "code" => true );

}

/**
 * Function returns the html markup for error messages
 *
 * @param $error_msg
 * @return string HTML
 */
function error_message_div( $error_msg ) {

    return '<div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-offset-1 col-xs-10">
                    <div class="error-container">
                        <div class="error-main">
                            <div class="error-description"> ' . $error_msg . '</div>
                           <!-- <div class="error-description-mini"> The page your looking for is not here </div>-->
                            <br>
                        </div>
                    </div>
                </div>
            </div>';
}

/**
 * Function to activate the user, set the user status to 0 and empty the user activation key
 *
 * @param $user_email
 */
function activate_user( $user_email ) {
    global $wpdb;

    $table_name = $wpdb->users;

    $wpdb->update(  $table_name,
                    array( 'user_status' => 0, 'user_activation_key' => ' ' ),
                    array( 'user_login' => $user_email ) );

}