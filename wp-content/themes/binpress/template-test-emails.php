<?php
/**
 * Template Name: Test emails
 */

function test_email_sending(){
    $user_email = "nutan@ajency.in";
    $new_subscription_id = "8ncrrm";
    $user_name = "Hermione Granger";
    // subscription_canceled_email($user_name,$user_email, $new_subscription_id);
    // subscription_active_email($user_name,$user_email, $new_subscription_id);
    // subscription_uncharged_email($user_name,$user_email, $new_subscription_id);
    // subscription_past_due_email($user_name,$user_email, $new_subscription_id);
    subscription_past_due_email($user_name,$user_email, $new_subscription_id);
    subscription_uncharged_email($user_name,$user_email, $new_subscription_id);

}
test_email_sending();
//  $subscription_id = "538zpw";
// // test_email_sending();
// $subscription = get_subscription_email_data($subscription_id);
// print_r($subscription);

// $domain_user_id = get_post_field( 'post_author', 329 );

// echo $post_author_id;

// $customer_details['email'] = "";
// $customer_details['name'] = "";
// if(empty($customer_details['email'])){
// 		$domain_id = get_domain_for_bt_subscription($new_subscription_id);
// 		$domain_user_id = get_post_field( 'post_author', $domain_id );
// 		$user = get_user_by( 'email', $email_id );
// 		$user_email = $user->user_email;
// 		$user_name = $user->display_name;	
// 	}
// 	else{
// 		$user_email = $customer_details['email'];
// 		$user_name = $customer_details['name'];		
// 	}


// 	subscription_canceled_email($user_name,$user_email, $new_subscription_id);

$data = get_subscription_email_data('8ncrrm');

print_r($data);


