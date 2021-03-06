<?php

function bt_subscription_went_past_due($new_subscription_id,$customer_details){
	global $wpdb;
	$table = 'subscription';

	$user_email = $customer_details['email'];
	$user_name = $customer_details['name'];

	$susbscription_row = $wpdb->get_row("SELECT * FROM ".$table." WHERE subscription_id = '".$new_subscription_id."'");

	if (count ($susbscription_row) > 0) {
		$wpdb->update( $table, 
			array( 'past_due' => 1 ),
			array( 'subscription_id' => $new_subscription_id ) );
		// $wpdb->show_errors(); 
		// $wpdb->print_error();
		
		subscription_past_due_email($user_name,$user_email, $new_subscription_id);
	} 

}

function bt_subscription_charged_successfully($new_subscription_id,$customer_details){
	
	$user_email = $customer_details['email'];
	$user_name = $customer_details['name'];
	subscription_charged_email($user_name,$user_email, $new_subscription_id);

}

function bt_subscription_charged_unsuccessfully($new_subscription_id,$customer_details){
	
	$user_email = $customer_details['email'];
	$user_name = $customer_details['name'];
	subscription_uncharged_email($user_name,$user_email, $new_subscription_id);

}

function bt_subscription_went_active($new_subscription_id,$customer_details){

	$user_email = $customer_details['email'];
	$user_name = $customer_details['name'];
	subscription_active_email($user_name,$user_email, $new_subscription_id);

}

function bt_subscription_cancelled($new_subscription_id,$customer_details){

	if(!empty($customer_details['email'])){
		$user_email = $customer_details['email'];
		$user_name = $customer_details['name'];	
		subscription_canceled_email($user_name,$user_email, $new_subscription_id);
	}

}
