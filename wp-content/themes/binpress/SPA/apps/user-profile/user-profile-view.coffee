#include the files for the app
define ['app'
        'text!apps/user-profile/templates/userprofile.html'], (App, userProfileTpl)->

    #start the app module
    App.module 'UserProfileAppView', (View, App)->

        # View class for showing user profile
        class View.UserProfileView extends Marionette.ItemView

            className : 'user-profile-container'

            template : userProfileTpl

            events :
                'click #save-user-profile' :->
                    data = Backbone.Syphon.serialize @
                    console.log data


