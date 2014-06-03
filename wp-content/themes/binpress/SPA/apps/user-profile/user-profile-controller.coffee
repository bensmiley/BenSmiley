#include the files for the app
define ['app'
        'base-controller'
        'apps/user-profile/user-profile-view'], (App, AppController, View)->

    #start the app module
    App.module "UserProfileApp", (UserProfileApp, App, BackBone, Marionette, $, _)->

        # Controller class for showing user profile
        class UserProfileController extends AppController

            initialize : (opts)->

                #get the user model for the current logged in user
                @usermodel = usermodel = App.request "get:user:model"

                #get user profile view
                @view = @getView @usermodel

                @show @view

            getView :(usermodel) ->
                new View.UserProfileView
                    model : usermodel


        #handler for showing the user profile : triggered from left nav region
        App.commands.setHandler "show:user:profile", (opts) ->
            new UserProfileController opts


