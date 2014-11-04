<?php

/*
 * Subscription active email
 */

function getvars_subscription_active($recipients_email,$comm_data){

    global $aj_comm;

    $email_id   = $aj_comm->get_communication_meta($comm_data['id'],'email_id');
    $name   = $aj_comm->get_communication_meta($comm_data['id'],'user_name');
    $new_subscription_id   = $aj_comm->get_communication_meta($comm_data['id'],'subscription_id');
    
    $subscription_active_email_details   = get_subscription_active_email_data($new_subscription_id);

    // $domain_name = "domain.com";
    // $name = "Hermione Granger";
    // $old_plan = "Pro Plan";
    // $new_plan = "Standard Plan";
    // $amount = "$ 40";
    // $plan_features = "$ 40";

    $domain_name = $subscription_active_email_details['domain_name'];
    $new_plan = $subscription_active_email_details['new_plan'];
    $amount = $subscription_active_email_details['amount'];
    $plan_features = $subscription_active_email_details['plan_features'];

    $subject    = 'ChatCat.io - Plan Change for '.$domain_name; //New Plan selected for <Domain Name>

    $template_data['name']          = 'subscription-activated'; // [slug] name or slug of a template that exists in the user's mandrill account
    $template_data['subject']       = $subject;
    $template_data['from_email']    = 'nutankamat769@gmail.com';
    $template_data['from_name']     = 'Chatcat.io';


    $template_data['merge'] = true;
    $template_data['global_merge_vars'] = array();
    $template_data['global_merge_vars'][] = array('name' => 'USERNAME','content' => $name);
    $template_data['global_merge_vars'][] = array('name' => 'OLD_PLAN','content' => $old_plan);
    $template_data['global_merge_vars'][] = array('name' => 'NEW_PLAN','content' => $new_plan);
    $template_data['global_merge_vars'][] = array('name' => 'DOMAIN_NAME','content' => $domain_name);
    $template_data['global_merge_vars'][] = array('name' => 'AMOUNT','content' => $amount);
    $template_data['global_merge_vars'][] = array('name' => 'PLAN_FEATURES','content' => $plan_features);


    return $template_data;
}