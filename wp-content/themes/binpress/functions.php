<?php
/**
 * binpress functions file
 *
 * @package    WordPress
 * @subpackage binpress
 * @since      binpress 1.0
 */

function binpress_theme_setup() {

    // load language
    load_theme_textdomain('binpress', get_template_directory() . '/languages');

    // add theme support
    add_theme_support('post-formats', array('image', 'quote', 'status', 'link'));
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list'));

    // define you image sizes here
    add_image_size('binpress-full-width', 1038, 576, TRUE);

    // This theme uses its own gallery styles.
    add_filter('use_default_gallery_style', '__return_false');

    // set the custom user roles for the site
    set_site_user_role();

}

add_action('setup_theme', 'binpress_theme_setup');


function binpress_after_init() {

    show_admin_bar(FALSE);
}

add_action('init', 'binpress_after_init');


if (is_development_environment()) {

    function binpress_dev_enqueue_scripts() {

        wp_enqueue_script("requirejs",
            get_template_directory_uri() . "/js/bower_components/requirejs/require.js",
            array(),
            get_current_version(),
            TRUE);

        wp_enqueue_script("require-config",
            get_template_directory_uri() . "/js/require.config.js",
            array("requirejs"));

        $module = get_module_name();

        wp_enqueue_script("$module-script",
            get_template_directory_uri() . "/js/{$module}.scripts.js",
            array("require-config"));

        // localized variables
        wp_localize_script("requirejs", "AJAXURL", admin_url("admin-ajax.php"));
    }

    add_action('wp_enqueue_scripts', 'binpress_dev_enqueue_scripts');

    function binpress_dev_enqueue_styles() {

        $module = get_module_name();

        wp_enqueue_style("$module-script", get_template_directory_uri() . "/css/{$module}.styles.css");

    }

    add_action('wp_enqueue_scripts', 'binpress_dev_enqueue_styles');
}

if (!is_development_environment()) {

    function binpress_production_enqueue_script() {

        $module = get_module_name();
        $path = get_template_directory_uri() . "/production/js/{$module}.scripts.min.js";

        if (is_single_page_app())
            $path = get_template_directory_uri() . "/production/spa/{$module}.spa.min.js";

        wp_enqueue_script("$module-script",
            $path,
            array(),
            get_current_version(),
            TRUE);

    }

    add_action('wp_enqueue_scripts', 'binpress_production_enqueue_script');

    function binpress_production_enqueue_styles() {

        $module = get_module_name();

        wp_enqueue_style("$module-styles",
            get_template_directory_uri() . "/production/css/{$module}.styles.min.css",
            array(),
            get_current_version(),
            TRUE);

    }

    add_action('wp_enqueue_scripts', 'binpress_production_enqueue_styles');
}


function is_development_environment() {

    if (defined('ENV') && ENV === "production")
        return FALSE;

    return TRUE;
}


function get_current_version() {

    global $wp_version;

    if (defined('VERSION'))
        return VERSION;

    return $wp_version;

}

function is_single_page_app() {
    // TODO: Application logic to identify if current page is a SPA

    return FALSE;

}


function get_module_name() {

    $module = "";

    // TODO: Handle with better logic here. Regex or something
    if (is_page())
        $module = sanitize_title(get_the_title());


    return $module;
}

/**
 *
 * Function to remove all the default user roles and
 * add only administrator and site member user role
 *
 */
function set_site_user_role() {

    // get all the user roles
    $roles = get_editable_roles();

    // remove all user roles except administrator
    foreach ($roles as $rolename => $role):
        if ($rolename != "administrator")
            remove_role($rolename);
    endforeach;

    // add custom role site member with no capabilities
    add_role('site-member', __('Site Member'), array());

}

// TODO: functions for user signup, move it later to proper folders
/**
 *
 * New User signup ajax handler
 * This ajax action will accept a POST request.
 * This action will always be triggerd by a non logged in user
 * hence, add_action("wp_ajax_nopriv_*")
 *
 * @return JSON success / or / failure
 */

function ajax_new_user_signup() {

    //check if it is a post request else return error
    if ('POST' !== $_SERVER ['REQUEST_METHOD'])
        wp_send_json(array('code' => 'ERROR', 'msg' => 'Invalid request'));

    $signupform_data = $_POST;

    //pick the user specific fields from the pos data
    $user_data = pick_user_fields($signupform_data);

    $user_email = $user_data['user_email'];

    //check if the user email id exists else return error
    $check_user_email = check_email_exists($user_email);
    if (!$check_user_email)
        wp_send_json(array('code' => 'ERROR', 'msg' => 'Email ID already exists'));

    // pass the user data to function create_new_user and capture return data
    $user_id = create_new_user($user_data);

    // check if user created, on success returns user_id : on error WP_Error object
    if (is_wp_error($user_id))
        wp_send_json(array('code' => 'ERROR', 'msg' => 'User not created'));

    //update the user status to 1, since user not activated
    update_user_status_in_db($user_id);

    // generate the unqie user avtivation key using user email
    $user_activation_key = generate_user_activation_key($user_email);

    //insert the user activation key into the user record
    set_user_activation_key($user_activation_key, $user_id);

    //insert user details in cron module table for sending mails through cron job
    set_user_details_for_mail($user_data);

    wp_send_json(array('code' => 'OK',
        'msg' => 'A email has been send to you.Please click on the link to
                                          confirm your account'));


}

add_action('wp_ajax_nopriv_new-user-signup', 'ajax_new_user_signup');

/**
 * This function will pick the user specific fields from an array and return
 *
 * @param array $signupform_data
 *
 * @return array user_data fields
 */
function pick_user_fields($signupform_data) {

    // return if passed argument is not an array
    if (!is_array($signupform_data))
        return array();

    // the user fields required to be stored
    $user_fields = array('user_name', 'user_email', 'user_password');

    $user_data = array();

    // map  the $signupform_data and pick user fields
    foreach ($signupform_data as $field => $value) {

        if (in_array($field, $user_fields))
            $user_data[$field] = $value;
    }

    return $user_data;
}

/**
 * The function will check if the user email exists
 *
 * returns true if email does not exists
 *
 * returns false if email does exists
 *
 * @param  array $userdata
 * @return bool true or bool false
 */
function check_email_exists($user_email) {

    $check_email_exists = email_exists($user_email);

    if ($check_email_exists == false) {
        // email does not exists
        return true;
    } else {
        // email  exists
        return false;
    }
}

/**
 * Function to  register a new user in system
 * Calls wp_insert_user WP function to make the record
 * @return int|WP_Error new user_id or WP_Error object
 */
function create_new_user($user_data) {

    // user_name is not captured, so use user_email ad the  user_name
    $user_data['user_login'] = $user_data['user_email'];

    // any new registered user must be a site member
    $user_data['role'] = 'site-member';

    // set the first and last name of the user
    $user_data['first_name'] = strip_user_first_name($user_data['user_name']);
    $user_data['last_name'] = strip_user_last_name($user_data['user_name']);

    // create the new user
    $user_id = wp_insert_user($user_data);

    if (is_wp_error($user_id))
        return $user_id;

    return $user_id;
}

/**
 * Function returns the first name of the user
 *
 * @param string $username
 * @return string $first_name
 */
function strip_user_first_name($username) {

    //explode the string
    $name = explode(" ", $username);

    $first_name = count($name) > 0 ? $name[0] : '';

    return $first_name;
}

/**
 *Function returns the last name of the user
 *
 * @param string $username
 * @return string $first_name
 */
function strip_user_last_name($username) {

    //explode the string
    $name = explode(" ", $username);

    $last_name = count($name) > 1 ? $name[1] : '';

    return $last_name;
}
//TODO: make this function resauable by passing  status value, can be resued in send_email function
/**
 * Function to update the user status on user creation
 *
 * set to 1, as user not activated
 *
 * @param $user_id
 */
function update_user_status_in_db($user_id) {
    global $wpdb;

    $wpdb->update($wpdb->users, array('user_status' => 1), array('ID' => $user_id));
}

/**
 * Function to generate the unique user-activation key using user email
 *
 * @param $user_email
 * @return string $key
 */
function generate_user_activation_key($user_email) {

    $salt = wp_generate_password(20); // 20 character "random" string

    $key = sha1($salt . $user_email . uniqid(time(), true));

    return $key;
}

/**
 *
 * Function to insert the user activation key for the user in DB
 *
 * @param $user_activation_key
 * @param $user_id
 */
function set_user_activation_key($user_activation_key, $user_id) {
    global $wpdb;

    $wpdb->update($wpdb->users,
        array('user_activation_key' => $user_activation_key),
        array('ID' => $user_id)
    );

}

//TODO: seperate each inserts into separte functions
/**
 * Function to insert the user details into table cron_module
 *
 */
function set_user_details_for_mail($user_data) {
    global $wpdb;

    $admin_email = get_option('admin_email');

    // insert the user details for sending user email in cron_module table
    $wpdb->insert('cron_module',
        array(
            'email_type' => 'new-user-activation',
            'email_id' => $user_data['user_email'],
            'status' => '1'
        ));

    // insert the user details for sending the admin email to notify new user signup in cron_module table
    $wpdb->insert('cron_module',
        array(
            'email_type' => 'admin-newuser-notification',
            'email_id' => $admin_email,
            'status' => '1'
        ));

    //check if insert successfull
    if ($wpdb->insert_id != false)
        $admin_email_id = $wpdb->insert_id;

    // insert the user meta details for sending the admin email to notify new user signup in cron_module_meta table
    $wpdb->insert('cron_module_meta',
        array(
            'cron_module_ID' => $admin_email_id,
            'meta_key' => $user_data['user_name'],
            'meta_value' => $user_data['user_email']
        ));

}

function send_email_through_cron() {
    //send_mail_cron();
}

add_action('CRON_SEND_EMAIL', 'send_email_through_cron');

/**
 * Function to send emails on user signup through cron
 */
function send_mail_cron() {

    global $wpdb;

    $query = "SELECT * FROM cron_module WHERE status = 1";

    $pending_emails = $wpdb->get_results($query, ARRAY_A);

    foreach ($pending_emails as $pending_email) {

        switch ($pending_email['email_type']) {

            case "new-user-activation":
                $user_data = get_user_data($pending_email['email_id']);
                $mail_body = get_user_activation_mail_conent($user_data);
                $subject = "Activate your Account on BenSmiley";
                send_email($pending_email['email_id'], $subject, $mail_body,$pending_email['ID']);
                break;
        }

    }

}

function get_user_activation_mail_conent($user_data) {

    $body = sprintf(__('Hi  %s'), $user_data->display_name) . "\r\n\r\n";
    $body .= __('Thank you for creating an account with BenSmiley.
              Please confirm your email address by following the link below:') . "\r\n\r\n";

    $body .= '<' . site_url("user-activation?action=activate-user&key=$user_data->user_activation_key
                &login=" . rawurlencode($user_data->user_login), 'login') . ">\r\n";

    $body .= sprintf(__("If you're not %s or didn't request verification, you can ignore this email."),
            $user_data->display_name) . "\r\n\r\n";

    $body .= __('If you have any questions please feel free to contact on support@BenSmiley.com') . "\r\n\r\n";

    $body .= __('Regards,') . "\r\n\r\n";

    $body .= __('BenSmiley team') . "\r\n\r\n";

    return $body;

}

//TODO : put the number of parametrs passed into a array and send
function send_email($recipient, $subject, $mail_body,$mail_id) {
    global $wpdb;

    if(wp_mail($recipient, $subject, $mail_body)){

        $wpdb->update('cron_module', array('status' => 0), array('ID' => $mail_id));
    }

}

function get_user_data($user_email) {

    $user_data = get_user_by('email', $user_email);

    return $user_data;
}


/***
 *
 * Function to log in user into the site
 * */
function ajax_user_login() { /*
    if ( is_user_logged_in() ) {

        $blog     = get_active_blog_for_user( get_current_user_id() );
        $blogUrl  = $blog->siteurl;
        $response = array( "code" => "OK", 'blog_url' => $blogUrl, 'msg' => 'User already logged in' );
        wp_send_json( $response );
    }


    $pd_email = trim( $_POST[ 'pdemail' ] );
    $pd_pass  = trim( $_POST[ 'pdpass' ] );

    $credentials = array();

    $credentials[ 'user_login' ]    = $pd_email;
    $credentials[ 'user_password' ] = $pd_pass;

    $user_ = get_user_by( 'email', $pd_email );

    if ( $user_ ) {
        $user = wp_signon( $credentials );

        if ( is_wp_error( $user ) ) {
            $msg      = "The email / password doesn't seem right. Check if your caps is on and try again.";
            $response = array( 'code' => "FAILED", 'user' => $user_->user_login . $pd_pass, 'msg' => $msg );
            wp_send_json( $response );
        } else {
            $blog     = get_active_blog_for_user( $user->ID );
            $blog_url = $blog->siteurl;
            $response = array( "code" => "OK", 'blog_url' => $blog_url, 'msg' => 'Successful Login' );
            wp_send_json( $response );
        }
    } else {
        $msg      = "The email / password doesn't seem right. Check if your caps is on and try again.";
        $response = array( 'code' => "FAILED", 'msg' => $msg );
        wp_send_json( $response );
    }cx
   */
    wp_send_json(array('code' => 'OK'));

}

add_action('wp_ajax_user-login', 'ajax_user_login');
add_action('wp_ajax_nopriv_user-login', 'ajax_user_login');






