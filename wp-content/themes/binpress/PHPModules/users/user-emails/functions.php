<?php

function new_user_activation_email($user_data,$user_activation_key){

    global $aj_comm;

    $user_email = $user_data['user_email'];
    $user_name = $user_data['user_name'];

    $user = get_user_by( 'email', $user_email );

    $link = site_url( "user-activation?action=activate-user&key=" . $user_activation_key .
            "&login=" . rawurlencode( $user->user_login ), 'login' );

    $meta_data = array(
        'email_id' => $user_email,
        'user_name' => $user_name,
        'link' => $link
    );

    $comm_data = array(
        'component' => 'chatcat_users',
        'communication_type' => 'new_user_activation'
    );


    $recipient_emails =  array(
                            array(
                                'user_id' => $user->ID,
                                'type' => 'email',
                                'value' => $user->user_email,
                                'status' => 'linedup'
                            )
                        );

    $aj_comm->create_communication($comm_data,$meta_data,$recipient_emails);
}

function user_password_reset_email($user_data,$user_activation_key){

    global $aj_comm;

    $user_email = $user_data['user_email'];
    $user_name = $user_data['user_name'];

    $user = get_user_by( 'email', $user_email );

    $link = site_url( "reset-password?action=reset-password&key=" . $user_activation_key .
            "&login=" . rawurlencode( $user->user_login ), 'login' );

    $meta_data = array(
        'email_id' => $user_email,
        'user_name' => $user_name,
        'link' => $link
    );

    $comm_data = array(
        'component' => 'chatcat_users',
        'communication_type' => 'user_password_reset'
    );


    $recipient_emails =  array(
                            array(
                                'user_id' => $user->ID,
                                'type' => 'email',
                                'value' => $user->user_email,
                                'status' => 'linedup'
                            )
                        );

    $aj_comm->create_communication($comm_data,$meta_data,$recipient_emails);
}

function new_user_welcome_email($user_data){

    global $aj_comm;

    $user_email = $user_data['user_email'];
    $user_name = $user_data['user_name'];

    $user = get_user_by( 'email', $user_email );

    $meta_data = array(
        'email_id' => $user_email,
        'user_name' => $user_name
    );

    $comm_data = array(
        'component' => 'chatcat_users',
        'communication_type' => 'new_user_welcome'
    );


    $recipient_emails =  array(
                            array(
                                'user_id' => $user->ID,
                                'type' => 'email',
                                'value' => $user->user_email,
                                'status' => 'linedup'
                            )
                        );

    $aj_comm->create_communication($comm_data,$meta_data,$recipient_emails);
}


function admin_newuser_notification_email($user_data){

    global $aj_comm;

    $admin_email = get_option( 'admin_email' );

    $user_email = $user_data['user_email'];
    $user_name = $user_data['user_name'];
    
    $admin_user = get_user_by( 'email', $admin_email );

    $meta_data = array(
        'email_id' => $user_email,
        'user_name' => $user_name
    );

    $comm_data = array(
        'component' => 'chatcat_users',
        'communication_type' => 'admin_newuser_notification'
    );


    $recipient_emails =  array(
                            array(
                                'user_id' => $admin_user->ID,
                                'type' => 'email',
                                'value' => $admin_user->user_email,
                                'status' => 'linedup'
                            )
                        );

    $aj_comm->create_communication($comm_data,$meta_data,$recipient_emails);
}