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
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
    <![endif]-->
    <?php wp_head(); ?>

</head>
<body>
<div class="site">


    <div id="header-region"></div>
    <!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container row">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar" id="main-menu">
            <!-- BEGIN MINI-PROFILE -->
            <div class="page-sidebar-wrapper" id="main-menu-wrapper">
                <!-- END MINI-PROFILE -->
                <!-- BEGIN SIDEBAR MENU -->
                <p class="menu-title">BROWSE <span class="pull-right"><a href="javascript:;"><i
                                class="fa fa-refresh"></i></a></span></p>
                <ul>
                    <li class="start"><a href="index.html"> <i class="fa fa-user"></i> <span
                                class="title">User Profile</span> <span class="selected"></span> <span
                                class="arrow"></span> </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
                <!-- END SIDEBAR MENU -->
            </div>
        </div>
        <a href="#" class="scrollup">Scroll</a>

        <!-- END SIDEBAR -->
        <!-- BEGIN PAGE CONTAINER-->
        <div class="page-content">
            <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
            <div id="portlet-config" class="modal hide">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button"></button>
                    <h3>Widget Settings</h3>
                </div>
                <div class="modal-body"> Widget settings form goes here</div>
            </div>
            <div class="clearfix"></div>
            <div class="content">
                <ul class="breadcrumb">
                    <li>
                        <p>YOU ARE HERE</p>
                    </li>
                    <li><a href="#" class="active">User Profile</a></li>
                </ul>
                <div class="page-title"><i class="icon-custom-left"></i>

                    <h3>Form - <span class="semi-bold">Elements</span></h3>
                </div>
                <!--<div class="page-title">
                  <h3>User - <span class="semi-bold">Profile</span></h3>
                </div>-->
                <!-- BEGIN BASIC FORM ELEMENTS-->
                <div class="row">
                    <div class="col-md-9">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h4><span class="semi-bold">Profile</span></h4>
                            </div>
                            <div class="grid-body no-border"><br>

                                <div class="row">
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                        <div class="form-group">
                                            <label class="form-label">Your Name</label>

                                            <div class="controls">
                                                <input type="text" class="form-control"
                                                       placeholder="e.g. Mona Lisa Portrait">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Email</label>

                                            <div class="controls">
                                                <input type="text" class="form-control"
                                                       placeholder="e.g. some@example.com">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Old Password</label>

                                            <div class="controls">
                                                <input type="password" class="form-control" placeholder="**********">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">New Password</label>

                                            <div class="controls">
                                                <input type="password" class="form-control" placeholder="**********">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password</label>

                                            <div class="controls">
                                                <input type="password" class="form-control" placeholder="**********">
                                            </div>
                                        </div>
                                        <div class="pull-right">
                                            <a id="save_prof" class="btn btn-primary btn-cons" data-colorpicker-guid="1"
                                               href="#" data-color-format="hex" data-color="rgb(255, 255, 255)">Save</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="profile-wrapper pull-right"><img class="m-b-10" width="90"
                                                                                     height="90"
                                                                                     data-src-retina="assets/img/profiles/avatar2x.jpg"
                                                                                     data-src="assets/img/profiles/avatar.jpg"
                                                                                     alt=""
                                                                                     src="assets/img/profiles/avatar.jpg">

                                            <div class="clearfix"></div>
                                            <a id="save_prof" class="m-t-10" href="#" data-color-format="hex">Click to
                                                add/edit Profile Photo</a>
                                        </div>


                                    </div>
                                </div>
                                <div class=" m-t-20">
                                    <a id="save_prof" class="pull-right" href="#" data-color-format="hex">Delete
                                        Account</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- END BASIC FORM ELEMENTS-->

                <!-- END PAGE -->
            </div>
        </div>
    </div>

    <!-- END CONTAINER -->

</div>
<?php wp_footer(); ?>
</body>
</html>