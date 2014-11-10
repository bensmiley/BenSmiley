<?php

require_once get_theme_root()."/binpress/vendor/braintree/braintree_php/lib/Braintree.php" ;
require "functions.php";

function ajax_braintree_webhook() {

	Braintree_Configuration::environment(BT_ENVIRONMENT);
	Braintree_Configuration::merchantId(BT_MERCHANT_ID);
	Braintree_Configuration::publicKey(BT_PUBLIC_KEY);
	Braintree_Configuration::privateKey(BT_PRIVATE_KEY);

	
	//Verify webhook
	if(isset($_GET["bt_challenge"])) {
		echo(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));
	}

	// //Test webhooks
	// $sampleNotification = Braintree_WebhookTesting::sampleNotification(
	// 	Braintree_WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
	// 	'7d6syw'
	// 	);

	if(isset($_POST["bt_signature"]) && isset($_POST["bt_payload"]))
	{
		//Parse the recieved payload and signature
		$webhookNotification = Braintree_WebhookNotification::parse(
			$_POST["bt_signature"], $_POST["bt_payload"]
			);

		// //Test webhook data
		// $webhookNotification = Braintree_WebhookNotification::parse(
		// 	$sampleNotification['signature'],
		// 	$sampleNotification['payload']
		// 	);
		$new_subscription_id = $webhookNotification->subscription->id;
		$date_time = $webhookNotification->timestamp->format('Y-m-d H:i:s');
		$webhook_kind = $webhookNotification->kind;
		$customer_transactions = $webhookNotification->subscription->transactions; 
		foreach ($customer_transactions as $item) 
		{ 
			$customer_email = $item->customerDetails->email;
			$customer_name = $item->customerDetails->firstName;
			$customer_id = $item->customerDetails->id;
		}

		$customer_details = array(
			'id' => $customer_id, 
			'email' => $customer_email, 
			'name' => $customer_name, 
			);

		//make function calls based on the webhook kind
		switch ($webhook_kind) {
			case 'subscription_charged_successfully':
				echo "Charged successfully";
				// bt_subscription_charged_successfully($new_subscription_id,$customer_details);
				break;

			case 'subscription_charged_unsuccessfully':
				echo "Not Charged successfully";
				// bt_subscription_charged_unsuccessfully($new_subscription_id,$customer_details);
				break;

			case 'subscription_went_active':
				echo "Subscription went active";

				bt_subscription_went_active($new_subscription_id,$customer_details);
				break;

			case 'subscription_went_past_due':
				echo "Subscription went past due";
				bt_subscription_went_past_due($new_subscription_id);
				break;

			case 'subscription_canceled':
				echo "Subscription cancelled";
				bt_subscription_cancelled($new_subscription_id,$customer_details);
				break;				

			default:
				echo "No change";
				break;
		}

		
	}
	
	wp_die();
}

add_action( 'wp_ajax_nopriv_braintree_webhook', 'ajax_braintree_webhook' );
add_action( 'wp_ajax_braintree_webhook', 'ajax_braintree_webhook' );