<?php
/**
 * binpress functions file
 *
 * @package    WordPress
 * @subpackage binpress
 * @since      binpress 1.0
 */

/**
 * PHP Modules Loader
 */
require_once 'PHPModules/users/signup/ajax.php';
require_once 'PHPModules/users/login/ajax.php';
require_once 'PHPModules/users/forgot-password/ajax.php';
require_once 'PHPModules/users/user-activation/functions.php';
require_once 'PHPModules/users/user-profile/ajax.php';
require_once 'PHPModules/users/billing/ajax.php';
require_once 'PHPModules/domains/ajax.php';
require_once 'PHPModules/groups/ajax.php';
require_once 'PHPModules/cron/send-email.php';
require_once 'PHPModules/payment/ajax.php';
require_once 'PHPModules/braintree/main-config.php';
require_once 'PHPModules/plans/ajax.php';
require_once 'PHPModules/subscription/ajax.php';
require_once 'PHPModules/API/ajax.php';
require_once 'PHPModules/braintree_webhook/ajax.php';

function binpress_theme_setup() {

    // load language
    load_theme_textdomain( 'binpress', get_template_directory() . '/languages' );

    // add theme support
    add_theme_support( 'post-formats', array( 'image', 'quote', 'status', 'link' ) );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'menus' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

    // define you image sizes here
    add_image_size( 'binpress-full-width', 1038, 576, TRUE );

    // This theme uses its own gallery styles.
    add_filter( 'use_default_gallery_style', '__return_false' );

    // set the custom user roles for the site
    //set_site_user_role();

}

add_action( 'after_setup_theme', 'binpress_theme_setup' );

function arphabet_widgets_init() {

    register_sidebar( array(
        'name' => 'Page Sidebar',
        'id' => 'sidebar-2'
    ) );

    register_sidebar( array( 'name' => 'Footer Area 1', 'id' => 'sidebar-4','before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>' ) );

    register_sidebar( array( 'name' => 'Footer Area 2', 'id' => 'sidebar-5','before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>' ) );

    register_sidebar( array( 'name' => 'Footer Area 3', 'id' => 'sidebar-6','before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>' ) );

}
//add_action( 'widgets_init', 'arphabet_widgets_init' );


function binpress_after_init() {

    show_admin_bar( FALSE );

    // add a custom post type:domain
    register_domain_post();

    // add a custom taxonomy:plan
    register_plan_taxonomy();

    // add terms for plan taxonomy
    register_terms_for_plans();

    // add custom data for each of the terms of plan taxonomy
//    add_data_to_plan_taxonomy_terms();
}

add_action( 'init', 'binpress_after_init' );


if ( is_development_environment() ) {

    function binpress_dev_enqueue_scripts() {

        if(!is_page('dashboard') && !is_page('user-activation') && !is_page('home') && !is_page('reset-password'))
        return;

        // TODO: handle with better logic to define patterns and folder names
        $module = get_module_name();
        $spa_pages = array( 'dashboard' );

        $pattern = in_array( $module, $spa_pages ) ? 'spa' : 'scripts';

        $folder_name = $pattern === 'spa' ? 'SPA' : 'js';

        wp_enqueue_script( "requirejs",
            get_template_directory_uri() . "/js/bower_components/requirejs/require.js",
            array(),
            get_current_version(),
            TRUE );

        wp_enqueue_script( "require-config",
            get_template_directory_uri() . "/{$folder_name}/require.config.js",
            array( "requirejs" ) );


        wp_enqueue_script( "$module-script",
            get_template_directory_uri() . "/{$folder_name}/{$module}.{$pattern}.js",
            array( "require-config" ) );

        create_local_scripts( "requirejs" );


    }

    add_action( 'wp_enqueue_scripts', 'binpress_dev_enqueue_scripts' );

    function binpress_dev_enqueue_styles() {

        if(!is_page('dashboard') && !is_page('user-activation') && !is_page('home') && !is_page('reset-password'))
        return;

        $module = get_module_name();

        wp_enqueue_style( "$module-style", get_template_directory_uri() . "/css/{$module}.styles.css" );

    }

    add_action( 'wp_enqueue_scripts', 'binpress_dev_enqueue_styles' );
}

if ( !is_development_environment() ) {

    function binpress_production_enqueue_script() {

        if(!is_page('dashboard') && !is_page('user-activation') && !is_page('home') && !is_page('reset-password'))
        return;

        $module = get_module_name();
        $path = get_template_directory_uri() . "/production/js/{$module}.scripts.min.js";

        if ( is_single_page_app( $module ) )
            $path = get_template_directory_uri() . "/production/spa/{$module}.spa.min.js";

        wp_enqueue_script( "$module-script",
            $path,
            array(),
            get_current_version(),
            TRUE );

        create_local_scripts( "$module-script" );
    }


    add_action( 'wp_enqueue_scripts', 'binpress_production_enqueue_script' );

    function binpress_production_enqueue_styles() {

        if(!is_page('dashboard') && !is_page('user-activation') && !is_page('home') && !is_page('reset-password'))
        return;

        $module = get_module_name();

        wp_enqueue_style( "$module-style",
            get_template_directory_uri() . "/production/css/{$module}.styles.min.css",
            array(),
            get_current_version(),
            "screen" );

    }

    add_action( 'wp_enqueue_scripts', 'binpress_production_enqueue_styles' );
}

function change_template_directory_uri($template_dir_uri, $template, $theme_root_uri){
    

    if( is_page('dashboard') || is_page('user-activation') || is_page('home') || is_page('reset-password')){
        $template_dir_uri = site_url('wp-content/themes/binpress');
    }
    else{
        $template_dir_uri = site_url('wp-content/themes/ben-smiley');
    }

    return   $template_dir_uri;

}
add_filter('template_directory_uri', 'change_template_directory_uri', 100, 3);

function change_template_directory($template_dir, $template, $theme_root ){
    if( is_page('dashboard') || 
        is_page('user-activation') || 
        is_page('home') || 
        is_page('reset-password')){
        $template_dir = ABSPATH . 'wp-content/themes/binpress';
    }
    else{
        $template_dir = ABSPATH . 'wp-content/themes/ben-smiley';
    }
    return $template_dir;
}
add_filter('template_directory', 'change_template_directory', 100, 3 );

function create_local_scripts( $handle ) {
    // localized variables
    wp_localize_script( $handle, "AJAXURL", admin_url( "admin-ajax.php" ) );
    wp_localize_script( $handle, "SITEURL", site_url() );
    wp_localize_script( $handle, "SITENAME", 'Chatcat.io' );
    wp_localize_script( $handle, "CSEK", BT_CSEK );

    wp_localize_script( $handle, "LOGOPATH", 'http://placehold.it/100x40' );
    wp_localize_script( $handle, "UPLOADURL", admin_url( "async-upload.php" ) );
    wp_localize_script( $handle, "_WPNONCE", wp_create_nonce( 'media-form' ) );
    if ( is_user_logged_in() && is_page_template( 'template-dashboard.php' ) )
        wp_localize_script( $handle, "CURRENTUSERDATA", get_current_user_data() );
}

function is_development_environment() {

    if ( defined( 'ENV' ) && ENV === "production" )
        return FALSE;

    return TRUE;
}


function get_current_version() {

    global $wp_version;

    if ( defined( 'VERSION' ) )
        return VERSION;

    return $wp_version;

}

function is_single_page_app( $module ) {

    // TODO: Application logic to identify if current page is a SPA
    $spa_pages = array( 'dashboard' );

    return in_array( $module, $spa_pages );

}


function get_module_name() {

    $module = "";

    // TODO: Handle with better logic here. Regex or something
    if ( is_page() )
        $module = sanitize_title( get_the_title() );


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
    foreach ( $roles as $role_name => $role ):
        if ( $role_name != "administrator" )
            remove_role( $role_name );
    endforeach;

    // add custom role site member with no capabilities
    add_role( 'site-member', __( 'Site Member' ), array() );

    // add custom capabilities to role site member
    add_capability_to_role();

}

add_action( 'admin_init', 'set_site_user_role' );

/**
 * Function to add custom capabilities to the user role : Site member
 */
function add_capability_to_role() {

    // gets the author role
    $role = get_role( 'site-member' );

    // This only works, because it accesses the class instance.
    $role->add_cap( 'upload_files' );
}

/**
 * Function to send all emails in the cron-module table through cron.
 *
 * Uses the plugin wp-crontrol
 *
 * calls the function send_mail_cron in send-email.php file
 *
 */
function send_email_through_cron() {

    send_mail_cron();
}

add_action( 'CRON_SEND_EMAIL', 'send_email_through_cron' );

/**
 * Function to add a custom taxonomy :plan
 */
function register_plan_taxonomy() {

    $labels = array(
        'name' => _x( 'Plans', 'taxonomy general name' ),
        'singular_name' => _x( 'Plan', 'taxonomy singular name' ),
        'search_items' => __( 'Search Plans', 'binpress' ),
        'popular_items' => __( 'Popular Plans', 'binpress' ),
        'all_items' => __( 'All Plans', 'binpress' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Plan', 'binpress' ),
        'update_item' => __( 'Update Plan', 'binpress' ),
        'add_new_item' => __( 'Add New Plan', 'binpress' ),
        'new_item_name' => __( 'New Plan Name', 'binpress' ),
        'separate_items_with_commas' => __( 'Separate plans with commas' ),
        'add_or_remove_items' => __( 'Add or remove plans', 'binpress' ),
        'choose_from_most_used' => __( 'Choose from the most used plans', 'binpress' ),
        'not_found' => __( 'No plans found.', 'binpress' ),
        'menu_name' => __( 'Plans', 'binpress' )
    );

    $args = array(
        'hierarchical' => FALSE,
        'labels' => $labels,
        'show_ui' => TRUE,
        'show_admin_column' => TRUE,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => TRUE,
        'rewrite' => array(
            'slug' => 'plan'
        )
    );
    register_taxonomy( 'plan', 'domain', $args );
}

/**
 * Function to add a custom post type:domain
 */
function register_domain_post() {
    $labels = array(
        'name' => 'Domains',
        'singular_name' => 'Domain',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Domain',
        'edit_item' => 'Edit Domain',
        'new_item' => 'New Domain',
        'all_items' => 'All Domains',
        'view_item' => 'View Domains',
        'search_items' => 'Search Domains',
        'not_found' => 'No Domains found',
        'not_found_in_trash' => 'No Domains found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Domains'
    );

    $args = array(
        'labels' => $labels,
        'label' => __( 'domain', "binpress" ),
        'public' => TRUE,
        'publicly_queryable' => TRUE,
        'show_ui' => TRUE,
        'show_in_menu' => TRUE,
        'query_var' => TRUE,
        'rewrite' => array(
            'slug' => 'domain'
        ),
        'capability_type' => 'post',
        'has_archive' => TRUE,
        'hierarchical' => FALSE,
        'menu_position' => null,
        'supports' => array(
            'title',
            'editor',
            'author',
            'thumbnail',
            'custom-fields'
        )
    );

    register_post_type( 'domain', $args );
}

/**
 * Function to insert custom terms for the taxonomy: plan
 */
function register_terms_for_plans() {

    wp_insert_term( 'Free', 'plan' );
    wp_insert_term( 'TestPlan1', 'plan' );
    wp_insert_term( 'TestPlan2', 'plan' );
    wp_insert_term( 'TestPlan3', 'plan' );
}

/**
 * Function to add custom data for each of the terms of plan taxonomy
 */
//function add_data_to_plan_taxonomy_terms() {
//
//    // add extra data for term Free
//    $term_free = get_term_by( 'name', 'Free', 'plan', ARRAY_A );
//    $term_free_data = maybe_serialize( array( 'Title' => 'Free Plan', 'Amount' => '0' ) );
//    add_option( $term_free[ 'term_id' ], $term_free_data );
//}

function redirect_if_required(){

    if(is_user_logged_in()){
        if(is_page('user-activation') || is_page('reset-password') || is_page('home')){
            wp_safe_redirect(site_url('dashboard/#profile'));
            die();
        }
    }

    if(!is_user_logged_in()){
        if(is_page('dashboard' )){
            wp_safe_redirect(site_url('home'));
            die();
        }
    }

}
add_action('template_redirect', 'redirect_if_required');


/**
 * Function to update a pending braintree subscription
 */

function update_pending_subscription() {
    global $wpdb;

    //Get all the pending subscriptions
    $pending_subscriptions = get_pending_subscription_list();
    
    //For each pending subscription
    foreach ($pending_subscriptions as $pending_subscription) {
        $pending_subscription_id = $pending_subscription['subscription_id'];
        $pending_domain_id = $pending_subscription['domain_id'];
        $pending_db_id = $pending_subscription['id'];

        // Get old active subscription record that needs to be cancelled
        $subscription_data = query_subscription_table( $pending_domain_id );
        $old_active_subscription_id = $subscription_data[ 'subscription_id' ];

        // echo "<br/>".$pending_domain_id."===============>"."<br/>".$pending_subscription_id."===============>".$old_active_subscription_id."<br/><br/>";
       
        //If subscription id is BENAJFREE
        if ($pending_subscription_id === 'BENAJFREE') {
            
            //Get braintree bill end date of previous active subscription
            $old_active_subscription_braintree_details = get_complete_subscription_details( $old_active_subscription_id );
            if ($old_active_subscription_braintree_details->success) {
                if (!empty( $old_active_subscription_braintree_details->billingPeriodEndDate )){
                        // echo "Pending is BENAJFREE - old active subs id is ==> ";
                        // echo $old_active_subscription_id;
                        // echo " ==> ";
                        // echo $old_active_subscription_braintree_details->status;
                        // echo "<br/>";

                        $bill_end_date = $old_active_subscription_braintree_details->billingPeriodEndDate->format( 'Y-m-d' );
                        //If bill end date of prev active subscription is a past date, then update subscription table to activate pending subscription and cancel active subscription
                        if (is_past_date($bill_end_date)) {
                            //update subscription table
                             update_subscription_table($old_active_subscription_id,$pending_subscription_id,$pending_domain_id);
                        }
                        
                }
            }
            
        }
        else{
            //Check braintree status of pending_subscription_id
            $pending_subscription_braintree_details = get_complete_subscription_details( $pending_subscription_id );

            if ($pending_subscription_braintree_details->success) {
                // echo $pending_subscription_id;
                // echo " ==> ";
                // echo $pending_subscription_braintree_details->status;
                // echo "<br/>";

                $braintree_subscription_status = $pending_subscription_braintree_details->status;

                //status - Active,Pending,Canceled,Past due,Expired

                //If pending subscription status is Active, then update subscription table to activate pending subscription and cancel active subscription
                if ($braintree_subscription_status==='Active') {
                    update_subscription_table($old_active_subscription_id,$pending_subscription_id,$pending_domain_id);
                }
                
            }
        }
    }

}

// add_action( 'init', 'update_pending_subscription' );
add_action( 'wp_update_pending_subscription', 'update_pending_subscription' );



function delete_unverified_users(){

    global $wpdb;

    $table_name = $wpdb->users;

    //get all users that have not activated their account after 5 days from registration
    $query = "SELECT ID, user_email, user_status FROM $table_name WHERE user_status = 1 AND DATE(user_registered) < DATE_SUB(CURDATE(), INTERVAL 5 DAY)";

    $users = $wpdb->get_results( $query, ARRAY_A );

    //Foreach such user, delete the user from wp db
    foreach ($users as $user) {
        
        require_once(ABSPATH.'wp-admin/includes/user.php' );
        $delete_status = wp_delete_user( $user['ID'] );

        // if ($delete_status) {
        //     echo "User ".$user['user_email']." deleted" ;
        //  } 
        
    }
}
add_action( 'cron_delete_unverified_users', 'delete_unverified_users' );

