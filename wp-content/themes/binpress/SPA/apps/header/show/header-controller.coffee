#FIXME: check the  user photo display
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
                new HeaderLayout

            showUserDisplayView : ->

                #get the user model for the current logged in user
                @userModel = msgbus.reqres.request "get:current:user:model"

                #listen to the model chnage event: when user profile updated
                @listenTo @userModel, "change", @handleUserUpdate

                #App.execute "when:fetched", @userModel, =>
                @userDisplayView = @getUserDisplayView @userModel

                @layout.userDisplayRegion.show @userDisplayView

                @listenTo @userDisplayView, "logout:clicked", @logoutUser

            getUserDisplayView : ( userModel ) ->
                new UserDisplayView
                    model : userModel

            handleUserUpdate : ( userModel ) =>
                @userDisplayView.triggerMethod "update:user:display", userModel

            logoutUser : ->
                options =
                    url : AJAXURL,
                    method : 'POST',
                    data :
                        action : 'user-logout'

                $.ajax( options ).done ( response )->
                    window.location.href = "#{response.redirect_url}/home"
                .fail ( resp )->
                    console.log 'error'


        # Header main layout
        class HeaderLayout extends Marionette.Layout

            template : '<div class="navbar-inner">
                            <div class="">
                                <div class="pull-left">
                                    <a href="index.html">
                                        <h3 class="p-l-30 ">Bensmiley</h3></a>
                                </div>
                                <div id="user-display"></div>
                            </div>
                        </div>'

            className : 'header navbar navbar-inverse'

            regions :
                userDisplayRegion : '#user-display'

        # return the header  layout instance
        HeaderLayout

        # View to display Logged in user name and user profile pic
        class UserDisplayView extends Marionette.ItemView

            template : '<div class="user-profile pull-left m-t-10">
                                        <img src="{{user_photo}}" alt="" width="35" height="35" id="user-photo">
                                    </div>
                                    <ul class="nav quick-section ">
                                        <li class="quicklinks">
                                            <a data-toggle="dropdown" class="dropdown-toggle  pull-right "
                                                href="#" id="user-options">
                                                <div class="pull-left">
                                                     <span class="bold display_name">{{display_name}}</span>
                                                </div>
                                               <div class="iconset top-down-arrow pull-left m-t-5 m-l-10"></div>
                                            </a>
                                            <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                                                <li>
                                                    <a href="#logout" id="logout">
                                                    <i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>'

            className : 'pull-right'

            events :
                'click #logout' : ->
                    @trigger "logout:clicked"

            onUpdateUserDisplay : ( userModel )->
                displayName = userModel.get 'display_name'
                userPhoto = userModel.get 'user_photo'
                @$el.find( '.display_name' ).text displayName
                @$el.find( '#user-photo' ).attr "src" : userPhoto


        # return theuser display view instance
        UserDisplayView


