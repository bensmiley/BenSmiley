<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/14/14
 * Time: 4:46 PM
 */
function get_all_plans() {

    $braintree_plan = array();

    $plans = Braintree_Plan::all();

    foreach ( $plans as $key => $plan ) {

        $braintree_plan[ $key ][ 'plan_id' ] = $plan->id;
        $braintree_plan[ $key ][ 'name' ] = $plan->name;
        $braintree_plan[ $key ][ 'description' ] = $plan->description;
        $braintree_plan[ $key ][ 'price' ] = $plan->price;

    }

    return $braintree_plan;
}

function get_plan_name_by_id( $plan_id ) {

    $plans = Braintree_Plan::all();

    foreach ( $plans as $plan ) {
        if($plan->id == $plan_id ){
            return $plan->name;
        }
    }
}