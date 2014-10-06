<?php

require_once get_theme_root()."/binpress/vendor/braintree/braintree_php/lib/Braintree.php" ;
require "functions.php";

function ajax_braintree_webhook() {

	Braintree_Configuration::environment(BT_ENVIRONMENT);
	Braintree_Configuration::merchantId(BT_MERCHANT_ID);
	Braintree_Configuration::publicKey(BT_PUBLIC_KEY);
	Braintree_Configuration::privateKey(BT_PRIVATE_KEY);

	if(isset($_GET["bt_challenge"])) {
		echo(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));
	}

	if(
		isset($_POST["bt_signature"]) &&
		isset($_POST["bt_payload"])
		) {
		$webhookNotification = Braintree_WebhookNotification::parse(
			$_POST["bt_signature"], $_POST["bt_payload"]
			);

	
	global $wpdb;
	$new_subscription_id = $webhookNotification->subscription->id;
	$date_time = $webhookNotification->timestamp->format('Y-m-d H:i:s');
	$webhook_kind = $webhookNotification->kind;

    $table_name = 'subscription';

    $wpdb->insert( $table_name,
        array(
            'domain_id' => 12345,
            'subscription_id' => $new_subscription_id,
            'datetime' => $date_time,
            'status' => $webhook_kind
        ) );
}
wp_die();
}

add_action( 'wp_ajax_nopriv_braintree_webhook', 'ajax_braintree_webhook' );
add_action( 'wp_ajax_braintree_webhook', 'ajax_braintree_webhook' );