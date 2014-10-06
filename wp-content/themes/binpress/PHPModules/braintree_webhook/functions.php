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
