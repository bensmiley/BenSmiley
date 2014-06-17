<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/17/14
 * Time: 12:23 PM
 */

require "functions.php";


function ajax_fetch_all_plans() {

    $braintree_plans = get_all_plans();

    wp_send_json( array( 'code' => 'OK', 'data' => $braintree_plans ) );
}

add_action( 'wp_ajax_fetch-all-plans', 'ajax_fetch_all_plans' );