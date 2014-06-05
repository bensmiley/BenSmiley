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
require_once 'PHPModules/cron/send-email.php';

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
    set_site_user_role();

}

add_action( 'setup_theme', 'binpress_theme_setup' );


function binpress_after_init() {

    show_admin_bar( FALSE );
}

add_action( 'init', 'binpress_after_init' );


if (is_development_environment()) {

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

        // localized variables
        wp_localize_script( "requirejs", "AJAXURL", admin_url( "admin-ajax.php" ) );
        wp_localize_script( "requirejs", "UPLOADURL", admin_url( "async-upload.php" ) );
        wp_localize_script( "requirejs", "_WPNONCE", wp_create_nonce( 'media-form' ) );
    }

    add_action( 'wp_enqueue_scripts', 'binpress_dev_enqueue_scripts' );

    function binpress_dev_enqueue_styles() {

        $module = get_module_name();

        wp_enqueue_style( "$module-style", get_template_directory_uri() . "/css/{$module}.styles.css" );

    }

    add_action( 'wp_enqueue_scripts', 'binpress_dev_enqueue_styles' );
}

if (!is_development_environment()) {

    function binpress_production_enqueue_script() {

        $module = get_module_name();
        $path = get_template_directory_uri() . "/production/js/{$module}.scripts.min.js";

        if (is_single_page_app())
            $path = get_template_directory_uri() . "/production/spa/{$module}.spa.min.js";

        wp_enqueue_script( "$module-script",
            $path,
            array(),
            get_current_version(),
            TRUE );

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


function is_development_environment() {

    if (defined( 'ENV' ) && ENV === "production")
        return FALSE;

    return TRUE;
}


function get_current_version() {

    global $wp_version;

    if (defined( 'VERSION' ))
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
    foreach ($roles as $role_name => $role):
        if ($role_name != "administrator")
            remove_role( $role_name );
    endforeach;

    // add custom role site member with no capabilities
    add_role( 'site-member', __( 'Site Member' ), array() );

    add_capability_to_role();

}

//TODO: write proper comments
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


