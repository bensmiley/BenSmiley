<?php
/* ----------------------------------------------------------
Declare vars
------------------------------------------------------------- */
$all_users = array();
 
$blogusers = get_users( array( 'role'=>'site-member' ));
// Array of stdClass objects.
foreach ( $blogusers as $user ) {
	// $all_users[$user->ID] = $user->display_name." => ".$user->user_email;
	$all_users[] = array('user_id' => $user->ID,
						'display_name' => $user->display_name,
						'user_email' => $user->user_email
				 );
}

// print_r($all_users);
$billing_users = get_option('billing-users-chatcat');
$billing_users_arr = explode(',', $billing_users);

function theme_settings_init(){
	register_setting( 'theme_settings', 'theme_settings' );
    wp_enqueue_style("panel_style", content_url()."/themes/binpress/css/wp-dashboard.styles.css", false, "1.0", "all");
	wp_enqueue_script("panel_script", content_url()."/themes/binpress/PHPModules/admin_menu/panel_script.js", true, "1.0");
}
add_action( 'admin_init', 'theme_settings_init' );

function update_user_billing_option(){
	$selected_users = $_POST['selected_users'];
	update_option( 'billing-users-chatcat',$selected_users );
	$msg = '<p>User billing settings saved.</strong></p>';
    wp_send_json( array( 'code' => 'OK', 'msg' => $msg ) );
}
add_action( 'wp_ajax_update-billing-users-options', 'update_user_billing_option');

function add_settings_page() {
add_menu_page( __( 'User Billing Options' ), __( 'User Billing Options' ), 'manage_options', 'user_billing_settings', 'theme_settings_page');
}
add_action('admin_menu', 'add_settings_page');

function theme_settings_page() {
    global $all_users, $billing_users_arr;

    ?>
    <div class="wrap options_wrap">
        <div id="icon-options-general"></div>
        <h2><?php _e( 'User Billing options' ) //your admin panel title ?></h2>
        <div class="updated settings-error" id="setting-error-settings_updated"> </div>
        
        <div class="content_options">
        	<form method="post">
        		<div >
        			<table class="wp-list-table widefat fixed users">
        				<tr>
        					<th align="center"><input type="checkbox" id="selectall"/></th>
        					<th>User name</th>
        					<th>User Email</th>
        				</tr>
        				
        				<?php foreach ($all_users as $user) {

						if (in_array($user['user_id'], $billing_users_arr)) {
							$checked = 'checked';
						}
						else{
							$checked = '';
						}
        				?>
        				<tr>
        					<td align="center"><input type="checkbox" class="case" value="<?php echo $user['user_id']; ?>" <?php echo $checked?> /></td>
        					<td><?php echo $user['display_name']; ?></td>
        					<td><?php echo $user['user_email']; ?></td>
        				</tr>
                    	<?php } ?>
        				
        			</table>

        			<input name="save" type="button" value="Save" id="btn-save-users" class="button-primary action"/>
        		</div>
        		
        	</form>
        </div>
        
    </div>
    <?php
}