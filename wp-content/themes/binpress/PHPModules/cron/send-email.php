<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/1/14
 * Time: 3:28 PM
 *
 * File Description :  Contains list of functions for sending user and admin emails through cron
 */


/**
 * Function to insert the user details for the mail to be sent into table cron_module
 *
 * @param $user_data : format array('user_email'=>'','user_name'=>'');
 * @param $email_type
 */
function set_user_details_for_mail( $user_data, $email_type ) {
    global $wpdb;

    if ( $email_type == "admin-newuser-notification" ) {

        set_admin_email_details( $user_data );
    } else {
        // insert the data and set status to 1, since mail not send
        $wpdb->insert( 'cron_module',
            array(
                'email_type' => $email_type,
                'email_id' => $user_data[ 'user_email' ],
                'status' => '1'
            ) );

    }

}


/**
 * Function to insert the user details for sending admin mail in cron_module and cron_module meta table
 *
 * @param $user_data
 */
function set_admin_email_details( $user_data ) {
    global $wpdb;

    $admin_email = get_option( 'admin_email' );

    // insert the user details for sending the admin email to notify new user signup
    $wpdb->insert( 'cron_module',
        array(
            'email_type' => 'admin-newuser-notification',
            'email_id' => $admin_email,
            'status' => '1'
        ) );

    // get the ID of the last insert
    $admin_mail_id = $wpdb->insert_id;

    // insert the user meta details for sending the admin email to notify new user signup
    $wpdb->insert( 'cron_module_meta',
        array(
            'cron_module_ID' => $admin_mail_id,
            'meta_key' => $user_data[ 'user_name' ],
            'meta_value' => $user_data[ 'user_email' ]
        ) );
}

/**
 * Function to send emails through cron. This function is called from the functions.php file of the theme
 *
 * on add_action hook CRON_SEND_EMAIL
 */
function send_mail_cron() {

    global $wpdb;

    //get all the rows from table where status is 1, as all pending email status is set to 1
    $query = "SELECT * FROM cron_module WHERE status = 1";

    $pending_emails = $wpdb->get_results( $query, ARRAY_A );

    foreach ( $pending_emails as $pending_email ) {

        switch ( $pending_email[ 'email_type' ] ) {

            case "new-user-activation":
                $user_data = get_user_data( $pending_email[ 'email_id' ] );
                $mail_body = get_user_activation_mail_content( $user_data );
                $subject = "Activate your Account on BenSmiley";
                send_email( $pending_email[ 'email_id' ], $subject, $mail_body, $pending_email[ 'ID' ] );
                break;

            case "new-user-welcome":
                $user_data = get_user_data( $pending_email[ 'email_id' ] );
                $mail_body = get_user_welcome_mail_content( $user_data );
                $subject = "Welcome to BenSmiley";
                send_email( $pending_email[ 'email_id' ], $subject, $mail_body, $pending_email[ 'ID' ] );
                break;

            case "admin-newuser-notification":
                $user_data = get_user_data_for_admin_mail( $pending_email[ 'ID' ] );
                $mail_body = get_admin_newuser_mail_content( $user_data );
                $subject = "BenSmiley - New User Registration";
                send_email( $pending_email[ 'email_id' ], $subject, $mail_body, $pending_email[ 'ID' ] );
                break;

            case "user-password-reset":
                $user_data = get_user_data( $pending_email[ 'email_id' ] );
                $mail_body = get_password_reset_mail_content( $user_data );
                $subject = "Reset your BenSmiley password";
                send_email( $pending_email[ 'email_id' ], $subject, $mail_body, $pending_email[ 'ID' ] );
                break;
        }

    }

}

/**
 * Function to get the user data for the user email passed
 *
 * @param $user_email
 *
 * @return bool false if no user exists forthe email | WP_User object if user exists for the email
 */
function get_user_data( $user_email ) {

    $user_data = get_user_by( 'email', $user_email );

    return $user_data;
}

/**
 * Function to get the user data for admin  new user email
 *
 * $userdata contains the username and user email
 *
 * @param $user_email
 *
 * @return the user data in array format
 */
function get_user_data_for_admin_mail( $mail_id ) {

    global $wpdb;

    //get all the rows from table where cron_module_ID = mail Id passed to function
    $query = "SELECT * FROM cron_module_meta WHERE cron_module_ID = " . $mail_id;

    $user_email = $wpdb->get_results( $query, ARRAY_A );

    // get the user data based the email id present in $user_email[ 'meta_value' ]
    $user_data = get_user_data( $user_email[0][ 'meta_value' ] );

    return $user_data;
}


/**
 * Function to send the mail and update the mail status to 0 on successful mail send
 *
 * @param $recipient
 * @param $subject
 * @param $mail_body
 * @param $mail_id
 */
function send_email( $recipient, $subject, $mail_body, $mail_id ) {
    global $wpdb;
    add_filter( 'wp_mail_content_type', 'set_html_content_type' );
    if ( wp_mail( $recipient, $subject, $mail_body ) ) {

        $wpdb->update( 'cron_module', array( 'status' => 0 ), array( 'ID' => $mail_id ) );
    } else {
        echo 'no mail send';
    }
    remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

}

// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578


function set_html_content_type() {

    return 'text/html';
}

/**
 * Function to get the mail body content for user activation email
 *
 * @param $user_data
 * @return string of mail body content
 */
function get_user_activation_mail_content( $user_data ) {

    $body = sprintf( __( 'Hi  %s' ), $user_data->display_name ) . "<br>";
    $body .= __( 'Thank you for creating an account with BenSmiley.
              Please confirm your email address by clicking the following:' ) . "<br>";

    $link = site_url( "user-activation?action=activate-user&key=" . $user_data->user_activation_key .
            "&login=" . rawurlencode( $user_data->user_login ), 'login' );

    $body .= '<a target ="_blank" href='.$link.'>Click here to activate profile</a><br>';

    $body .= sprintf( __( "If you're not %s or didn't request verification, you can ignore this email." ),
            $user_data->display_name ) . "<br>";

    $body .= __( 'If you have any questions please feel free to contact on support@BenSmiley.com' ) . "<br>";

    $body .= __( 'Regards,' ) . "<br>";

    $body .= __( 'BenSmiley team' ) . "<br>";

    return $body;

}

/**
 * Function to get the mail body content for new user signup admin notification email
 *
 * @param $user_data
 * @return string of mail body content
 */
function get_admin_newuser_mail_content( $user_data ) {

    $body = __( 'Hi' ) . "<br>";

    $body .= __( 'A new User has registered on BenSmiley' ) . "<br>";
    $body .= __( 'User details : ' ) . "<br>";
    $body .= sprintf( __( 'Name: :   %s' ), $user_data->display_name ) . "<br>";
    $body .= sprintf( __( 'Email :   %s' ), $user_data->user_login ) . "<br>";

    $body .= __( 'Regards,' ) . "<br>";

    $body .= __( 'Team' ) . "<br>";

    return $body;
}

/**
 * Function to get the mail body content for new user welcome email
 *
 * @param $user_data
 * @return string of mail body content
 */
function get_user_welcome_mail_content( $user_data ) {

    $body = sprintf( __( 'Hi  %s' ), $user_data->display_name ) . "<br>";
    $body .= __( 'Welcome to BenSmiley. ' ) . "<br>";

    $body .= __( 'Your account has been successfully verified! You can
                    now login with the credentials provided by you at the time of registration
                to start using your account.' ) . "<br>";

    $body .= __( 'Start by following these 3 simple steps ' ) . "<br>";
    $body .= __( '1. Login to your account ' ) . "<br>";
    $body .= __( '2. Select a plan that is best suited for you.You can choose from our range of plans ' ) . "<br>";
    $body .= __( '3. Start adding domains & creating groups ' ) . "<br>";
    $body .= __( 'You can then chat, add/edit groups anytime on the go!  ' ) . "<br>";
    $body .= __( 'Meanwhile,if you have any queries please feel free to contact our team on number
                or email us at support@bensmiley.com.  ' ) . "<br>";

    $body .= __( 'Regards,' ) . "<br>";

    $body .= __( 'BenSmiley team' ) . "<br>";

    return $body;
}

/**
 * Function to get the mail body content for password reset requested by the user
 *
 * @param $user_data
 * @return string reset password mail body
 */
function get_password_reset_mail_content( $user_data ) {

    $body = sprintf( __( 'Hi  %s' ), $user_data->display_name ) . "<br>";

    $body .= __( 'You have requested a new password for Bensmiley.' ) . "<br>";

    $body .= __( 'To change your password' );

    $body .= __( ' use this link:' ) . "<br>";

    $link = site_url( "reset-password?action=reset-password&key=" . $user_data->user_activation_key .
            "&login=" . rawurlencode( $user_data->user_login ), 'login' );

    $body .= '<a target ="_blank" href='.$link.'>Click here to reset password</a><br>';

    $body .= __( 'Meanwhile,if you have any queries please feel free to contact our team on number
                or email us at support@bensmiley.com.  ' ) . "<br>";

    $body .= __( 'Regards,' ) . "<br>";

    $body .= __( 'BenSmiley team' ) . "<br>";

    return $body;
}