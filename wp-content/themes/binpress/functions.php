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
add_action( 'widgets_init', 'arphabet_widgets_init' );


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

        $module = get_module_name();

        wp_enqueue_style( "$module-style", get_template_directory_uri() . "/css/{$module}.styles.css" );

    }

    add_action( 'wp_enqueue_scripts', 'binpress_dev_enqueue_styles' );
}

if ( !is_development_environment() ) {

    function binpress_production_enqueue_script() {

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

        $module = get_module_name();

        wp_enqueue_style( "$module-style",
            get_template_directory_uri() . "/production/css/{$module}.styles.min.css",
            array(),
            get_current_version(),
            "screen" );

    }

    add_action( 'wp_enqueue_scripts', 'binpress_production_enqueue_styles' );
}

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
    if(is_front_page())
        $module = 'home';
    else if ( is_page() )
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