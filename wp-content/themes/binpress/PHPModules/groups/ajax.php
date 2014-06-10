<?php
/**
 * Created by PhpStorm.
 * User: Ansley
 * Date: 6/10/14
 * Time: 3:05 PM
 */


function ajax_create_domain_group(){

    $group_post_data = $_POST;

    $group_details = array('group_name'=>$group_post_data['group_name'],
        'group_description'=> $group_post_data['group_description']);

    $new_group= maybe_serialize($group_details);

    $groups_meta = get_post_meta( $group_post_data['domain_id'], 'groups',true );

    if(empty($groups_meta)){

        update_post_meta($group_post_data['domain_id'],'groups',$new_group);
        wp_send_json( array( 'code' => 'OK','data'=>$new_group ) );
    }

    $groups_array = maybe_unserialize($groups_meta);

    foreach($groups_array as $key=>$val):
        $new_groups_meta[$key] = $val;
    endforeach;

    $new_groups_meta[]= maybe_serialize($group_details);

    maybe_serialize($new_groups_meta);

    update_post_meta($group_post_data['domain_id'],'groups',$new_groups_meta);
    wp_send_json( array( 'code' => 'OK','data'=>$new_groups_meta ) );

}

add_action( 'wp_ajax_create-domain-group', 'ajax_create_domain_group' );