<?php

/*
 * Subscription active email
 */

function getvars_new_user_activation($recipients_email,$comm_data){

    global $aj_comm;

    $email_id   = $aj_comm->get_communication_meta($comm_data['id'],'email_id');
    $name   = $aj_comm->get_communication_meta($comm_data['id'],'user_name');
    $activation_link   = $aj_comm->get_communication_meta($comm_data['id'],'link');

    $subject = "Activate your Account on Chatcat.io";

    $template_data['name']          = 'new-user-activation'; // [slug] name or slug of a template that exists in the user's mandrill account
    $template_data['subject']       = $subject;
    $template_data['from_email']    = 'nutankamat769@gmail.com';
    $template_data['from_name']     = 'Chatcat.io';


    $template_data['merge'] = true;
    $template_data['global_merge_vars'] = array();
    $template_data['global_merge_vars'][] = array('name' => 'USERNAME','content' => $name);
    $template_data['global_merge_vars'][] = array('name' => 'ACTIVATION_LINK','content' => $activation_link);


    return $template_data;
}

function getvars_user_password_reset($recipients_email,$comm_data){

    global $aj_comm;

    $email_id   = $aj_comm->get_communication_meta($comm_data['id'],'email_id');
    $name   = $aj_comm->get_communication_meta($comm_data['id'],'user_name');
    $activation_link   = $aj_comm->get_communication_meta($comm_data['id'],'link');

    $subject = "Reset your Chatcat.io password";

    $template_data['name']          = 'user-password-reset'; // [slug] name or slug of a template that exists in the user's mandrill account
    $template_data['subject']       = $subject;
    $template_data['from_email']    = 'nutankamat769@gmail.com';
    $template_data['from_name']     = 'Chatcat.io';


    $template_data['merge'] = true;
    $template_data['global_merge_vars'] = array();
    $template_data['global_merge_vars'][] = array('name' => 'USERNAME','content' => $name);
    $template_data['global_merge_vars'][] = array('name' => 'ACTIVATION_LINK','content' => $activation_link);


    return $template_data;
}

function getvars_new_user_welcome($recipients_email,$comm_data){

    global $aj_comm;

    $email_id   = $aj_comm->get_communication_meta($comm_data['id'],'email_id');
    $name   = $aj_comm->get_communication_meta($comm_data['id'],'user_name');

    $subject = "Welcome to Chatcat.io";

    $template_data['name']          = 'new-user-welcome'; // [slug] name or slug of a template that exists in the user's mandrill account
    $template_data['subject']       = $subject;
    $template_data['from_email']    = 'nutankamat769@gmail.com';
    $template_data['from_name']     = 'Chatcat.io';


    $template_data['merge'] = true;
    $template_data['global_merge_vars'] = array();
    $template_data['global_merge_vars'][] = array('name' => 'USERNAME','content' => $name);


    return $template_data;
}

function getvars_admin_newuser_notification($recipients_email,$comm_data){

    global $aj_comm;

    $email_id   = $aj_comm->get_communication_meta($comm_data['id'],'email_id');
    $name   = $aj_comm->get_communication_meta($comm_data['id'],'user_name');

    $subject = "Chatcat.io - New User Registration";

    $template_data['name']          = 'admin-new-user-notification'; // [slug] name or slug of a template that exists in the user's mandrill account
    $template_data['subject']       = $subject;
    $template_data['from_email']    = 'nutankamat769@gmail.com';
    $template_data['from_name']     = 'Chatcat.io';


    $template_data['merge'] = true;
    $template_data['global_merge_vars'] = array();
    $template_data['global_merge_vars'][] = array('name' => 'USERNAME','content' => $name);
    $template_data['global_merge_vars'][] = array('name' => 'USEREMAIL','content' => $email_id);


    return $template_data;
}
