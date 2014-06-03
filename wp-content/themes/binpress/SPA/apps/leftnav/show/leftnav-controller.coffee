#include the files for the app
define ['app'
        'base-controller'], (App, AppController)->

    #start the app module
    App.module 'LeftNavApp.Show', (Show, App, Backbone, Marionette, $, _)->

        # Controller class for showing header region
        class Show.Controller extends AppController

            # initialize the controller
            initialize : (opt = {})->

                #get the layout for header
                @layout = @getLayout()

                @show @layout

            getLayout : ->
                new LeftNavView

        # Header main layout
        class LeftNavView extends Marionette.Layout

            template : '<div class="page-sidebar" id="main-menu">
                            <div class="page-sidebar-wrapper" id="main-menu-wrapper">
                                <ul>
                                    <li class="start">
                                        <a href="index.html"> <i class="fa fa-user"></i>
                                        <span class="title">User Profile</span>  <span class="selected"></span>
                                        <span class="arrow"></span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>'


