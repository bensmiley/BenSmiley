#include the files for the app
define ['app'
        'region-controller'
        'apps/user-profile/user-profile-view'], (App, AppController, View)->

    #start the app module
    App.module "UserProfileApp", (UserProfileApp, App, BackBone, Marionette, $, _)->

        # Controller class for showing user profile
        class UserProfileController extends AppController

            initialize : (opts)->

                #get the user model for the current logged in user
                @usermodel = usermodel = App.request "get:user:model"

                #get user profile view
                @layout = @getLayout @usermodel

                @listenTo @layout, "show", ->
                    App.execute "start:upload:app", region : @layout.userPhotoRegion

                @listenTo @layout, "save:user:profile:clicked", @saveUserProfile

                @show @layout

            getLayout : (usermodel) ->
                new View.UserProfileView
                    model : usermodel

            saveUserProfile : (userdata)->
                @usermodel.set userdata
                @usermodel.save null,
                    wait : true,
                    success : @showSuccess()
            showSuccess : ->
                @layout.triggerMethod "user:profile:updated"


        #handler for showing the user profile : triggered from left nav region
        App.commands.setHandler "show:user:profile", (opts) ->
            new UserProfileController opts


