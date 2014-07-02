#include the files for the app
define [ 'app'
         'msgbus'
         'regioncontroller'
         'apps/user-profile/edit/user-profile-view' ], ( App, msgbus, RegionController, UserProfileView )->

    #start the app module
    App.module "UserProfileApp.Edit", ( Edit, App, BackBone, Marionette, $, _ )->

        # Controller class for showing and editing the user profile
        class UserProfileController extends RegionController

            initialize : ( opts )->

                #get the user model for the current logged in user
                @userModel = msgbus.reqres.request "get:current:user:model"

                #get user profile layout
                @layout = @getLayout @userModel

                #listen to show of layout and start the upload app
                @listenTo @layout, "show", =>
                    App.execute "start:upload:app",
                        region : @layout.userPhotoRegion
                        model : @userModel

                # listen to user profile save click event
                @listenTo @layout, "save:user:profile:clicked", @saveUserProfile

                #show the layout
                @show @layout

            getLayout : ( userModel ) ->
                new UserProfileView
                    model : userModel

            saveUserProfile : ( userData )->
                @userModel.save userData,
                    wait : true
                    success : @showSuccess

            showSuccess : =>
                @layout.triggerMethod "user:profile:updated"


        App.commands.setHandler "show:user:profile", ( options ) ->
            new UserProfileController options


