<?php

/*
 * Subscription active email
 */

function getvars_subscription_active($recipients_email,$comm_data){

    global $aj_comm;

    $email_id   = $aj_comm->get_communication_meta($comm_data['id'],'email_id');
    $subscription_id   = $aj_comm->get_communication_meta($comm_data['id'],'subscription_id');
    $subscription_details   = get_subscription_details($subscription_id);

    $subject    = 'ChatCat Subscription activated for '.$domain_name; //New Plan selected for <Domain Name>
    $message    = 'Subscription is active';

    $template_data['name']          = 'subscription-activated'; // [slug] name or slug of a template that exists in the user's mandrill account
    $template_data['subject']       = 'Chatcat Subscription activated for '.$domain_name;
    $template_data['from_email']    = 'nutankamat769@gmail.com';
    $template_data['from_name']     = 'Chatcat.io';


    $template_data['merge'] = true;
    $template_data['global_merge_vars'] = array();
    $template_data['global_merge_vars'][] = array('name' => 'USERNAME','content' => $name);
    $template_data['global_merge_vars'][] = array('name' => 'DOMAIN_NAME','content' => $domain_name);
    $template_data['global_merge_vars'][] = array('name' => 'DOMAIN_URL','content' => $domain_url);
    $template_data['global_merge_vars'][] = array('name' => 'PLAN_NAME','content' => $plan_name);
    $template_data['global_merge_vars'][] = array('name' => 'PLAN_AMOUNT','content' => $plan_amount);
    $template_data['global_merge_vars'][] = array('name' => 'TRANSACTION_AMOUNT','content' => $transaction_amount);
    $template_data['global_merge_vars'][] = array('name' => 'BILL_NEXT_DATE','content' => $bill_next_date);


    return $template_data;
}