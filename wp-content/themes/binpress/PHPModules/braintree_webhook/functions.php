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

function bt_subscription_went_active($new_subscription_id,$date_time){

$to = "nutankamat769@gmail.com";
$subject = "Subscription is now active";
$content = "Your subscription".$new_subscription_id." is now active";
 
$status = wp_mail($to, $subject, $content);

}
