<?php
/**
 * binpress template for displaying the header
 *
 * @package WordPress
 * @subpackage binpress
 * @since binpress 1.0
 */
?>

<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie ie-no-support" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>
<html class="ie ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title(); ?></title>
    <meta name="viewport" content="width=device-width"/>
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico"/>
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
    <![endif]-->
    <?php wp_head(); ?>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body <?php body_class(); ?>>
<div class="site">

    <header class="site-header">
        <div class="header navbar navbar-inverse ">
            <?php if ( '' != get_custom_header()->url ) : ?>
                <img src="<?php header_image(); ?>" class="custom-header"
                     height="<?php echo get_custom_header()->height; ?>"
                     width="<?php echo get_custom_header()->width; ?>"
                     alt=""/>
            <?php endif; ?>

            <a class="logo" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>">
                <h1 class="blog-name">
                    <?php
                        $blog_name = get_bloginfo( 'name','display' );
                        echo ucfirst($blog_name);
                    ?>
                </h1>
            </a>

            <div class="menu"><?php

                $nav_menu = wp_nav_menu(
                    array(
                        'container' => 'nav',
                        'container_class' => 'main-menu',
                        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                        'theme_location' => 'main-menu',
                        'fallback_cb' => '__return_false',
                    )
                ); ?>

            </div>
            <!-- show user name and photo display if user logged in  -->
            <?php
            if ( is_user_logged_in() ):
                $userdata = get_current_user_data();
            ?>
            <div class="pull-right" style="margin-top:-55px;">
                <div class="user-profile pull-left m-t-10">
                    <img src="<?php echo $userdata['user_photo'];?>" alt="" width="35" height="35" id="user-photo">
                </div>
                <ul class="nav quick-section ">
                    <li class="quicklinks">
                        <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="javascript:void(0)" id="user-options">
                            <div class="pull-left">
                                <span class="bold display_name"><?php echo $userdata['display_name'];?></span>
                            </div>
                            <div class="iconset top-down-arrow pull-left m-t-5 m-l-10"></div>
                        </a>
						<ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options"> 
							<li> 
								<a href="#logout" id="logout"> <i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out </a> 
							</li> 
						</ul>
                    </li>
                </ul>
            </div>
            <?php  endif;?>
            <!-- end user name and photo display if user logged in  -->


        </div>
    </header>