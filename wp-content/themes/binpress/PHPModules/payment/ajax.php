<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/11/14
 * Time: 2:21 PM
 */

require_once get_template_directory().'/vendor/braintree/braintree_php/lib/Braintree.php';


Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('x8dwnhxhdmmhytj7');
Braintree_Configuration::publicKey('msc2t6xzfj2qdw59');
Braintree_Configuration::privateKey('5b8284e277f8c86552dcd6598e86e77c');

function ajax_make_payment(){
    $result = Braintree_Transaction::sale(array(
        "amount" => "1000.00",
        "creditCard" => array(
            "number" => '4111111111111111',
            "cvv" => '111',
            "expirationMonth" => '11',
            "expirationYear" => '2015'
        ),
        "options" => array(
            "submitForSettlement" => true
        )
    ));

    if ($result->success) {
        echo "Success! Transaction ID: " . $result->transaction->id;

        $result2 = Braintree_Customer::create(array(
            'firstName' => 'Fred',
            'lastName' => 'Jones',
            'creditCard' => array(
                'cardholderName' => 'Fred Jones',
                'number' => '4111111111111111',
                'cvv' => '123',
                'expirationDate' => '05/2020',
                'options' => array(
                    'verifyCard' => true
                )
            )
        ));

        if ($result2->success) {
            echo("Success! Customer ID: " . $result2->customer->id);
        } else {
            echo("Validation errors:<br/>");
            foreach (($result2->errors->deepAll()) as $error) {
                echo("- " . $error->message . "<br/>");
            }
        }







    } else if ($result->transaction) {
        echo("Error: " . $result->message);
        echo("<br/>");
        echo("Code: " . $result->transaction->processorResponseCode);
        wp_send_json("erroe");
    } else {
        echo("Validation errors:<br/>");
        foreach (($result->errors->deepAll()) as $error) {
            echo("- " . $error->message . "<br/>");
        }
        wp_send_json("erroe");
    }



    $result = Braintree_Customer::create(array(
        'firstName' => 'Fred',
        'lastName' => 'Jones',
        'creditCard' => array(
            'cardholderName' => 'Fred Jones',
            'number' => '4111111111111111',
            'cvv' => '123',
            'expirationDate' => '05/2020',
            'options' => array(
                'verifyCard' => true
            )
        )
    ));


}

add_action( 'wp_ajax_make-user-payment', 'ajax_make_payment' );