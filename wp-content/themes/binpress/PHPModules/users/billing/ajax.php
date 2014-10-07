<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/19/14
 * Time: 5:29 PM
 *
 * File Description :  Contains a list of ajax functions for the billing of user domains
 */
require "functions.php";
/**
 * Function to the customer billing details. Makes a call to the braintree API
 * and fetches the customer credit card details
 *
 */
function ajax_read_user_payment() {

    // retreive the user id through GET request
    $user_id = $_GET[ 'ID' ];

    //get the braintree customer id for the user
    $braintree_customer_id = get_user_meta( $user_id, 'braintree_customer_id', true );

    // get the credit card info for the customer
    $customer_credit_card_data = get_customer_credit_card_details( $braintree_customer_id );

    wp_send_json( array( 'code' => 'OK', 'data' => $customer_credit_card_data ) );
}

add_action( 'wp_ajax_read-user-payment', 'ajax_read_user_payment' );

/**
 * Function to make a payment by the user, when credit card used for the first time.
 *
 * The credit card array passed to function  create_credit_card_for_customer() is n the format:
 *
 *     [creditCardData] => Array
 * (
 * [creditCardNumber] => "encrypted value"
 * [cardholderName] => "encrypted value"
 * [expirationDate] => "encrypted value"
 * [creditCardCvv] => "encrypted value"
 * )
 *
 */
function ajax_user_new_payment() {

    //get $_POST data
    $selected_plan_id = $_POST[ 'selectedPlanId' ];
    $payment_method_nonce = $_POST[ 'paymentMethodNonce' ];
    $customer_id = $_POST[ 'customerId' ];
    $current_subscription_id = $_POST[ 'currentSubscriptionId' ];
    $selected_plan_name = $_POST[ 'selectedPlanName' ];
    $selected_plan_price = $_POST[ 'selectedPlanPrice' ];
    $domain_id = $_POST[ 'domainId' ];
    $active_plan_id = $_POST[ 'activePlanId' ];
    unset( $_POST[ 'action' ] );

    // create the credit card for the user
    $create_card = create_credit_card_for_customer( $customer_id, $payment_method_nonce );

    if ( $create_card[ 'code' ] == 'ERROR' )
            wp_send_json( array( 'code' => 'ERROR', 'msg' => $create_card[ 'msg' ] ) );

    // compare the price of active and current plans
    $price_compare = compare_plan_price( $selected_plan_id, $active_plan_id );

    // prepare the array to create subscriptions
    $subscription_array = array(
        'card_token' => $create_card[ 'credit_card_token' ],
        'domain_id' => $domain_id,
        'selected_plan_id' => $selected_plan_id,
        'selected_plan_name' => $selected_plan_name,
        'selected_plan_price' => $selected_plan_price,
        'current_subscription_id' => $current_subscription_id,
    );

    // if true make a active subscription else make pending subscription
    if ( !$price_compare )
        make_pending_subscription( $subscription_array );
    else
        make_active_subscription( $subscription_array );

    //====================old code begins============================
    // $credit_card_data = $_POST;

    // $card_data = $credit_card_data[ 'creditCardData' ];

    // unset( $credit_card_data[ 'action' ] );

    // $selected_plan_id = $credit_card_data[ 'selectedPlanId' ];

    // $selected_plan_name = $credit_card_data[ 'selectedPlanName' ];

    // $selected_plan_price = $credit_card_data[ 'selectedPlanPrice' ];

    // $active_plan_id = $credit_card_data[ 'activePlanId' ];

    // $domain_id = $credit_card_data[ 'domainId' ];

    // $current_subscription_id = $credit_card_data[ 'subscriptionId' ];

    // // create the credit card for the user
    // $card_token = create_credit_card_for_customer( $card_data );
    // if ( $card_token[ 'code' ] == 'ERROR' )
    //     wp_send_json( array( 'code' => 'OK', 'msg' => $card_token[ 'msg' ] ) );

    // // compare the price of active and current plans
    // $price_compare = compare_plan_price( $selected_plan_id, $active_plan_id );

    // // prepare the array to create subscriptions
    // $subscription_array = array(
    //     'card_token' => $card_token[ 'credit_card_token' ],
    //     'domain_id' => $domain_id,
    //     'selected_plan_id' => $selected_plan_id,
    //     'selected_plan_name' => $selected_plan_name,
    //     'selected_plan_price' => $selected_plan_price,
    //     'current_subscription_id' => $current_subscription_id,
    // );

    // // if true make a active subscription else make pending subscription
    // if ( !$price_compare )
        // make_pending_subscription( $subscription_array );
    // else
    //     make_active_subscription( $subscription_array );
    //====================old code ends===========================

}

add_action( 'wp_ajax_user-new-payment', 'ajax_user_new_payment' );

/**
 * Function to make a payment by the user, when credit card details are stored in vault.
 */
function ajax_user_make_payment() {

    $credit_card_data = $_POST;

    unset( $credit_card_data[ 'action' ] );

    $credit_card_token = $credit_card_data[ 'creditCardToken' ];

    $selected_plan_id = $credit_card_data[ 'selectedPlanId' ];

    $selected_plan_name = $credit_card_data[ 'selectedPlanName' ];

    $selected_plan_price = $credit_card_data[ 'selectedPlanPrice' ];

    $active_plan_id = $credit_card_data[ 'activePlanId' ];

    $domain_id = $credit_card_data[ 'domainId' ];

    $current_subscription_id = $credit_card_data[ 'subscriptionId' ];

    // compare the price of active and current plans
    $price_compare = compare_plan_price( $selected_plan_id, $active_plan_id );

    // prepare the array to create subscriptions
    $subscription_array = array(
        'card_token' => $credit_card_token,
        'domain_id' => $domain_id,
        'selected_plan_id' => $selected_plan_id,
        'selected_plan_name' => $selected_plan_name,
        'selected_plan_price' => $selected_plan_price,
        'current_subscription_id' => $current_subscription_id,
    );

    // if true make a active subscription else make pending subscription
    if ( !$price_compare )
        make_pending_subscription( $subscription_array );
    else
        make_active_subscription( $subscription_array );
}

add_action( 'wp_ajax_user-make-payment', 'ajax_user_make_payment' );

/**
 * Function to cancel a subscription in braintree
 */
function ajax_cancel_subscription() {

    $pending_subscription_id = $_POST[ 'subscriptionId' ];
    $domain_id = $_POST[ 'domainId' ];

    $cancel_subscription = cancel_subscription_in_braintree( $pending_subscription_id );

    if ( $cancel_subscription[ 'code' ] ) {
        //delete the entry for the previously pending subscription in db
        delete_subscription( $pending_subscription_id );

        //Add BENAJFREE as pending subscription in db
        create_pending_free_subscription( $domain_id );

        wp_send_json( array( 'code' => 'OK', 'data' => 'Subscription cancelled' ) );
    } else
        wp_send_json( array( 'code' => 'ERROR', 'data' => $cancel_subscription[ 'msg' ] ) );

}

add_action( 'wp_ajax_cancel-subscription', 'ajax_cancel_subscription' );


/**
 * Function to cancel a subscription in braintree
 */
function ajax_cancel_paid_subscription() {

    $current_subscription_id = $_POST[ 'activeSubscriptionId' ];
    $domain_id = $_POST[ 'domainId' ];

    
    //Get pending subscription for the domainid
    $pending_subscription = get_pending_subscription($domain_id);

    //If there is a pending subscription
    if(!empty($pending_subscription)){
        $pending_subscription_id = $pending_subscription['subscription_id'];

        if ($pending_subscription_id!=='BENAJFREE') {
            $cancel_subscription = cancel_subscription_in_braintree( $pending_subscription_id );
        }

        //delete the entry for the previously pending subscription in db
        delete_subscription( $pending_subscription_id );
        
    }

    //cancel active subscription in braintree if not already cancelled
     $active_subscription_braintree_details = get_complete_subscription_details( $current_subscription_id );

     $braintree_subscription_status = $active_subscription_braintree_details->status;

     if ($braintree_subscription_status==='Active') {
           $cancel_subscription = cancel_subscription_in_braintree( $current_subscription_id );

           if ( $cancel_subscription[ 'code' ] ) {
            
                //Add BENAJFREE as pending subscription in db
                create_pending_free_subscription( $domain_id );

                wp_send_json( array( 'code' => 'OK', 'data' => 'Paid Subscription cancelled' ) );
            } 
            else{
                wp_send_json( array( 'code' => 'ERROR', 'data' => $cancel_subscription[ 'msg' ] ) );
            }

     }
     else{
            //delete the entry for the previously pending subscription in db
            delete_subscription( $pending_subscription_id );

            //Add BENAJFREE as pending subscription in db
            create_pending_free_subscription( $domain_id );

            wp_send_json( array( 'code' => 'OK', 'data' => 'Paid Subscription cancelled' ) ); 
     }

}

add_action( 'wp_ajax_cancel-paid-subscription', 'ajax_cancel_paid_subscription' );

