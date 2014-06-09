<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/1/14
 * Time: 10:28 PM
 *
 * File Description :  Contains list of functions called from the ajax file for the forgot password form ajax actions
 */


/**
<<<<<<< HEAD
 * Function to generate a new hashed user activation key
 *
 * @return string $hashed_user_activation_key
 */
function generate_hashed_user_activation_key() {

    //generate the random key to be used for new user activation key
    $key = wp_generate_password( 20, false );

    // generate the hash user activation key
    if (empty($wp_hasher)) { // FIXME: $wp_hasher is not defined before. Recheck the code here

        require_once ABSPATH . 'wp-includes/class-phpass.php';

        $wp_hasher = new PasswordHash(8, true);
    }
    $hashed_user_activation_key = $wp_hasher->HashPassword( $key );

    return $hashed_user_activation_key;
}


/**
=======
>>>>>>> 6888129e2109d9ed9ad8b860bce95e11f998974a
 * Function to update user activation key in db for the user with the
 *
 * new hashed user activation key
 *
 * @param $hashed_user_activation_key
 * @param $user_login
 */
//TODO: use core wp functions to update the user activation key in database
<<<<<<< HEAD
function update_new_user_activation_key_db( $hashed_user_activation_key, $user_login ) {
    global $wpdb;

    //update user activation key
    $wpdb->update(  $wpdb->users,
                    array( 'user_activation_key' => $hashed_user_activation_key ),
                    array( 'user_login' => $user_login ) );
=======
function update_new_user_activation_key_db( $user_activation_key, $user_login ) {
    global $wpdb;

    //update user activation key
    $wpdb->update( $wpdb->users,
        array( 'user_activation_key' => $user_activation_key ),
        array( 'user_login' => $user_login ) );
>>>>>>> 6888129e2109d9ed9ad8b860bce95e11f998974a
}

/**
 * Function to check the user status in the db for password reset.
 *
 * If status is 0: user is active
 *
 * If status is 1 : inactive user
 *
 * @param $user_data
 * @return array containing the error or success message
 */
function check_user_status_password_reset( $user_data ) {

    if ( $user_data->user_status == 1 ) {

        $error_msg = array( "code" => false, "msg" => "User not activated.Activate your account" );
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
 *
 * @return array containing the error or success message
 */
function validate_reset_password_key( $key, $user_data ) {

    $user_activation_key_check = check_password_reset_key( $key, $user_data );

<<<<<<< HEAD
    if (is_wp_error( $user_activation_key_check ))
=======
    if ( is_wp_error( $user_activation_key_check ) )
>>>>>>> 6888129e2109d9ed9ad8b860bce95e11f998974a
        return array( "code" => false, "msg" => "Invalid activation key" );

    return array( "code" => true );
}


/**
 * Function to clear the activation key for the user on successful validation
 *
 * of reset password url
 *
 * @param $user_email
 */
//TODO: use core wp functions to reset the user activation key
function reset_activation_key( $user_email ) {
    global $wpdb;

    $table_name = $wpdb->users;

<<<<<<< HEAD
    $wpdb->update( $table_name, array( 'user_activation_key' => ' ' ), array( 'user_login' => $user_email ) );
=======
    $wpdb->update( $table_name, array( 'user_activation_key' => ' ' ),
        array( 'user_login' => $user_email ) );
>>>>>>> 6888129e2109d9ed9ad8b860bce95e11f998974a
}
