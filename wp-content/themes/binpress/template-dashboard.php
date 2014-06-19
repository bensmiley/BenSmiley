<?php
/**
 * Template Name: Dashboard
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
	<link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" />
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
    <![endif]-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <?php wp_head(); ?>

</head>
<body>
<div class="site">


    <!-- Display the header region of the dashboard -->
    <div id="header-region"></div>

    <!-- BEGIN CONTAINER -->
    <div class="page-container row">

        <!-- Display the left nav menu region of the dashboard -->
        <div id="left-nav-region"></div>

        <a href="#" class="scrollup">Scroll</a>


        <!-- BEGIN PAGE CONTAINER-->
        <div class="page-content">

            <div class="content">

                <!-- Display the bread crumbs on the dashboard -->
                <div id="breadcrumb-region">
                    <!--                <ul class="breadcrumb">-->
                    <!--                    <li>-->
                    <!--                        <p>YOU ARE HERE</p>-->
                    <!--                    </li>-->
                    <!--                    <li><a href="#" class="active">User Profile</a></li>-->
                    <!--                </ul>-->
                </div>

                <!-- Display the main content region which will load all the sub apps of the dashboard -->
                <div id="main-content-region"></div>

            </div>
        </div>
        <!-- BEGIN PAGE CONTAINER-->
    </div>
    <!-- END CONTAINER -->


</div>
<?php wp_footer(); ?>
</body>
</html>