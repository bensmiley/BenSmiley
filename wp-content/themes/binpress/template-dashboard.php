<?php
/**
 * Template Name: Dashboard
 */

get_header();
?>
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse ">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <div class="navbar-inner">
            <div class="header-seperation">
                <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
                    <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" > <div class="iconset top-menu-toggle-white"></div> </a> </li>
                </ul>
                <!-- BEGIN LOGO -->
                <div class="pull-left">
                    <a href="index.html"><!--<img src="assets/img/logo.png" class="logo" alt=""  data-src="assets/img/logo.png" data-src-retina="assets/img/logo2x.png" width="106" height="21"/>--><h3 class="p-l-20 text-white">Logo</h3></a>
                    <!-- END LOGO -->
                </div>

                <div class="pull-right">
                    <div class="user-profile pull-left m-t-10">
                        <img src="assets/img/profiles/avatar_small.jpg"  alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35" />
                    </div>
                    <ul class="nav quick-section ">
                        <li class="quicklinks">
                            <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
                                <div class="pull-left">John <span class="bold">Smith</span></div> &nbsp; <div class="iconset top-down-arrow pull-left m-t-5 m-l-10"></div>
                            </a>
                            <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                                <li><a href="user-profile.html"> My Account</a>
                                </li>
                                <li><a href="calender.html">My Calendar</a>
                                </li>
                                <li><a href="email.html"> My Inbox&nbsp;&nbsp;<span class="badge badge-important animated bounceIn">2</span></a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="login.html"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container row">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar" id="main-menu">
            <!-- BEGIN MINI-PROFILE -->
            <div class="page-sidebar-wrapper" id="main-menu-wrapper">
                <!-- END MINI-PROFILE -->
                <!-- BEGIN SIDEBAR MENU -->
                <p class="menu-title">BROWSE <span class="pull-right"><a href="javascript:;"><i class="fa fa-refresh"></i></a></span></p>
                <ul>
                    <li class="start"> <a href="index.html"> <i class="fa fa-user"></i> <span class="title">User Profile</span> <span class="selected"></span> <span class="arrow"></span> </a>
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
                <div class="modal-body"> Widget settings form goes here </div>
            </div>
            <div class="clearfix"></div>
            <div class="content">
                <ul class="breadcrumb">
                    <li>
                        <p>YOU ARE HERE</p>
                    </li>
                    <li><a href="#" class="active">User Profile</a> </li>
                </ul>
                <div class="page-title"> <i class="icon-custom-left"></i>
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
                            <div class="grid-body no-border"> <br>
                                <div class="row">
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                        <div class="form-group">
                                            <label class="form-label">Your Name</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" placeholder="e.g. Mona Lisa Portrait">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" placeholder="e.g. some@example.com">
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
                                            <a id="save_prof" class="btn btn-primary btn-cons" data-colorpicker-guid="1" href="#" data-color-format="hex" data-color="rgb(255, 255, 255)">Save</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="profile-wrapper pull-right"> <img class="m-b-10" width="90" height="90" data-src-retina="assets/img/profiles/avatar2x.jpg" data-src="assets/img/profiles/avatar.jpg" alt="" src="assets/img/profiles/avatar.jpg">
                                            <div class="clearfix"></div>
                                            <a id="save_prof" class="m-t-10"  href="#" data-color-format="hex">Click to add/edit Profile Photo</a>
                                        </div>


                                    </div>
                                </div>
                                <div class=" m-t-20">
                                    <a id="save_prof" class="pull-right"  href="#" data-color-format="hex">Delete Account</a>
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



<?php

 get_footer(); ?>