<?php

function bt_subscription_went_past_due($new_subscription_id){
	global $wpdb;
	$table = 'subscription';

	$susbscription_row = $wpdb->get_row("SELECT * FROM ".$table." WHERE subscription_id = '".$new_subscription_id."'");

	if (count ($susbscription_row) > 0) {
		$wpdb->update( $table, 
					   array( 'past_due' => 1 ),
        			   array( 'subscription_id' => $new_subscription_id ) );
		// $wpdb->show_errors(); 
		// $wpdb->print_error();
	} 
}

function bt_subscription_charged_successfully($new_subscription_id){
	$to = "nutankamat769@gmail.com";
	$subject = "Subscription is charged";
	$content = "Your subscription".$new_subscription_id." is charged successfully";
	 
	$status = wp_mail($to, $subject, $content);

	}

function bt_subscription_went_active($new_subscription_id,$customer_details){

$user_email = $customer_details['email'];
$user_name = $customer_details['name'];
subscription_active_email($user_name,$user_email, $new_subscription_id);

}

function bt_subscription_cancelled($new_subscription_id,$customer_details){

	if(empty($customer_details['email'])){
		$domain_id = get_domain_for_bt_subscription($new_subscription_id);
		$domain_user_id = get_post_field( 'post_author', $domain_id );
		$user = get_user_by( 'email', $email_id );
		$user_email = $user->user_email;
		$user_name = $user->display_name;	
	}
	else{
		$user_email = $customer_details['email'];
		$user_name = $customer_details['name'];		
	}

	$to = "nutankamat769@gmail.com";
	$subject = "Subscription is cancelled";
	$content = "Your subscription".$new_subscription_id." is cancelled successfully and your emails id is ".$user_email;

	$status = wp_mail($to, $subject, $content);


	subscription_canceled_email($user_name,$user_email, $new_subscription_id);

}
