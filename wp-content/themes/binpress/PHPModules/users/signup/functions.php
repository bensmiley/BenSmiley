<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/1/14
 * Time: 3:04 PM
 *
 * File Description :Contains list of functions called from the ajax file for the sign up form ajax actions
 */


/**
 * This function will pick the user specific fields from an array and return
 *
 * an array with the required user specific fields
 *
 * @param array $signup_form_data
 *
 * @return array user_data fields
 */
function pick_user_fields( $signup_form_data ) {

    // return if passed argument is not an array
    if (!is_array( $signup_form_data ))
        return array();

    // the user fields required to be stored
    $user_fields = array( 'user_name', 'user_email', 'user_pass' );

    $user_data = array();

    // map  the $signupform_data and pick user fields
    foreach ($signup_form_data as $field => $value) {

        if (in_array( $field, $user_fields ))
            $user_data[$field] = $value;
    }

    return $user_data;
}

/**
 * Function to  register a new user in the system
 *
 * Calls wp_insert_user WP function to create the record
 *
 * @return new user_id or (int)WP_Error object
 */
function create_new_user( $user_data ) {

    // user_name is not captured, so use user_email as the  user_name
    $user_data['user_login'] = $user_data['user_email'];

    // any new registered user must be assigned the role as site member
    $user_data['role'] = 'site-member';

    // set the first and last name of the user
    $user_data['first_name'] = strip_user_first_name( $user_data['user_name'] );
    $user_data['last_name'] = strip_user_last_name( $user_data['user_name'] );

    // create the new user
    $user_id = wp_insert_user( $user_data );

    //add user meta for user profile pic
    update_user_meta( $user_id, 'user_photo_id', '' );

    return $user_id;
}

/**
 * Function returns the first name of the user
 *
 * @param string $user_name
 * @return string $first_name
 */
function strip_user_first_name( $user_name ) {

    //explode the string
    $name = explode( " ", $user_name );

    $first_name = count( $name ) > 0 ? $name[0] : '';

    return $first_name;
}

/**
 *Function returns the last name of the user
 *
 * @param string $user_name
 * @return string $last_name
 */
function strip_user_last_name( $user_name ) {

    //explode the string
    $name = explode( " ", $user_name );

    $last_name = count( $name ) > 1 ? $name[1] : '';

    return $last_name;
}

/**
 * Function to update the user status on user creation
 *
 * set to 1, as user not activated
 *
 * @param $user_id
 */
function update_user_status_in_db( $user_id ) {
    global $wpdb;

    $wpdb->update( $wpdb->users, array( 'user_status' => 1 ), array( 'ID' => $user_id ) );
}

/**
 * Function to generate the unique user-activation key using user email
 *
 * @param $user_email
 * @return string $key
 */
function generate_user_activation_key( $user_email ) {

    $salt = wp_generate_password( 20 ); // 20 character "random" string

    // generate key using SHA1 encryption
    $key = sha1( $salt . $user_email . uniqid( time(), true ) );

    return $key;
}

/**
 *
 * Function to insert the user activation key for the user in DB
 *
 * @param $user_activation_key
 * @param $user_id
 */
function set_user_activation_key( $user_activation_key, $user_id ) {
    global $wpdb;

    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $user_activation_key ), array( 'ID' => $user_id ) );

}