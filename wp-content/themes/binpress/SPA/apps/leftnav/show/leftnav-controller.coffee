#include the files for the app
define [ 'app', 'regioncontroller' ], ( App, RegionController )->

    #start the app module
    App.module 'LeftNavApp.Show', ( Show, App, Backbone, Marionette, $, _ )->

        # Controller class for showing left nav menu region
        class Show.Controller extends RegionController

            # initialize the controller
            initialize : ( opt = {} )->

                #get the view for left nav menu
                @view = @getView()

                @show @view

            getView : ->
                new LeftNavView


        # Left nav menu view
        class LeftNavView extends Marionette.CompositeView

            template : '<div class="page-sidebar-wrapper" id="main-menu-wrapper">
                            <ul>
                                <li class="start">
                                    <a href="#domains" id="user-domains">
                                        <i class="fa fa-globe"></i>
                                        <span class="title">My Domains</span>
                                        <span class="selected"></span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li class="start">
                                    <a href="#profile" id="user-profile">
                                        <i class="fa fa-user"></i>
                                        <span class="title">User Profile</span>
                                        <span class="selected"></span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                     </div>'

            className : 'page-sidebar'

        # return the instance of the leftnav view
        LeftNavView


