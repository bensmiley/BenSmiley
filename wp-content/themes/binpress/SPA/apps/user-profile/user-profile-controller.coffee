#include the files for the app
define ['app'
        'regioncontroller'
        'apps/user-profile/user-profile-view'], (App, RegionController, View)->

    #start the app module
    App.module "UserProfileApp", (UserProfileApp, App, BackBone, Marionette, $, _)->

        # Controller class for showing user profile
        class UserProfileController extends RegionController

            initialize : (opts)->

                #get the user model for the current logged in user
                @usermodel = App.request "get:current:user:model"

                #get user profile layout
                @layout = @getLayout @usermodel

                @listenTo @layout, "show", ->
                    App.execute "start:upload:app",
                        region : @layout.userPhotoRegion
                        model : @usermodel

                # listen to user profile save click event
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


