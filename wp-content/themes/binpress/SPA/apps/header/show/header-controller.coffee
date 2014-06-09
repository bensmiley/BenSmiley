#include the files for the app
define [ 'app',
         'regioncontroller',
         'behaviors/closewarn'
         'msgbus' ], ( App, AppController, CloseWarn, msgbus )->

    #start the app module
    App.module 'HeaderApp.Show', ( Show, App, Backbone, Marionette, $, _ )->

        # Controller class for showing header region
        class Show.Controller extends AppController

            # initialize the controller
            initialize : ( opt = {} )->

                #get the layout for header
                @layout = @getLayout()

                #listen to show event of layout and render the user display view
                @listenTo @layout, "show", @showUserDisplayView

                @show @layout

            getLayout : ->
                new HeaderView

            showUserDisplayView : ->

                #get the user model for the current logged in user
                @usermodel = msgbus.reqres.request "get:current:user:model"

                @userDisplayView = @getUserDisplayView @usermodel

                #                App.execute "when:fetched", [@usermodel], =>
                @layout.userDisplayRegion.show @userDisplayView

            getUserDisplayView : ( usermodel ) ->
                new UserDisplayView
                    model : usermodel

        # Header main layout
        class HeaderView extends Marionette.Layout

            template : '<div class="navbar-inner">
                            <div class="">
                                <div class="pull-left">
                                    <a href="index.html">
                                        <h3 class="p-l-20 text-white">Logo</h3></a>
                                </div>
                                <div id="userDisplay"></div>
                            </div>
                        </div>'

            className : 'header navbar navbar-inverse'

            regions :
                userDisplayRegion : '#userDisplay'
        HeaderView

        # View to display Logged in user name and user profile pic
        class UserDisplayView extends Marionette.ItemView

            template : '<div class="user-profile pull-left m-t-10">
                            <img src="{{user_photo}}" alt=""
                            data-src="{{user_photo}}"
                            data-src-retina="{{user_photo}}" width="35" height="35">
                        </div>
                        <ul class="nav quick-section ">
                            <li class="quicklinks">
                                <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
                                    <div class="pull-left"> <span class="bold">{{display_name}}</span></div>
                                    &nbsp;
                                    <div class="iconset top-down-arrow pull-left m-t-5 m-l-10"></div>
                                </a>
                                <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                                    <li><a href="login.html"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
                                </ul>
                            </li>
                        </ul>'

            className : 'pull-right'

        UserDisplayView


