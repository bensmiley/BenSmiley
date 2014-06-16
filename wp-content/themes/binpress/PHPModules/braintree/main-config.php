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

require_once get_template_directory().'/vendor/braintree/braintree_php/lib/Braintree.php';


Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('x8dwnhxhdmmhytj7');
Braintree_Configuration::publicKey('msc2t6xzfj2qdw59');
Braintree_Configuration::privateKey('5b8284e277f8c86552dcd6598e86e77c');


require_once 'customer.php';