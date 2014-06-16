<?php
/**
 * Created by PhpStorm.
 * User: Mahima
 * Date: 6/16/14
 * Time: 6:22 PM
 */

//TODO: write proper coments in foiles
function get_subscription_details_for_domain( $domain_id ) {

    global $wpdb;

    $query = "SELECT * FROM subscription WHERE domain_id = ".$domain_id." ORDER BY id DESC LIMIT 0, 1";

    $result = $wpdb->get_results($query,ARRAY_A);

    if(is_null($result))
        return array();

   echo '<pre>';
    print_r($result);

}