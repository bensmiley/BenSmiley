<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/13/14
 * Time: 12:27 PM
 *
 * File Description : The main config file for using Braintree API with the theme. The API keys
 *                    from the sandbox account of Braintree are added here.
 */

require_once ABSPATH  .'wp-content/themes/binpress/vendor/braintree/braintree_php/lib/Braintree.php';


Braintree_Configuration::environment(BT_ENVIRONMENT);
Braintree_Configuration::merchantId(BT_MERCHANT_ID);
Braintree_Configuration::publicKey(BT_PUBLIC_KEY);
Braintree_Configuration::privateKey(BT_PRIVATE_KEY);


/**
 * Include all the custom files required for braintree transaction and API calls
 */
require_once 'customer.php';
require_once 'plans.php';
require_once 'subscription.php';
require_once 'payment.php';

function generate_client_token(){
    $clientToken = Braintree_ClientToken::generate();
    return $clientToken;
}
