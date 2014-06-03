#include the files for the app
define ['app'
        'region-controller'], (App, AppController)->

    #start the app module
    App.module 'LeftNavApp.Show', (Show, App, Backbone, Marionette, $, _)->

        # Controller class for showing left nav nenu region
        class Show.Controller extends AppController

            # initialize the controller
            initialize : (opt = {})->

                #get the layout for left nav menu
                @layout = @getLayout()

                #listen to layout click events
                @listenTo @layout, "user:profile:clicked", ->
                    App.execute "show:user:profile" , region : App.mainContentRegion

                @show @layout

            getLayout : ->
                new LeftNavView

        # Header main layout
        class LeftNavView extends Marionette.Layout

            template : '<div class="page-sidebar-wrapper" id="main-menu-wrapper">
                            <ul>
                                <li class="start">
                                    <a href="javascript:void(0)" id="user-profile">
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

            id : 'main-menu'

            events :
                'click #user-profile' : ->
                    #trigger user profile click event to controller
                    @trigger "user:profile:clicked"



