#include the files for the app
define [ 'app'
         'msgbus'
         'apps/user-profile/edit/user-profile-controller' ], ( App, msgbus )->

    #start the app module
    App.module 'UserProfileApp', ( UserProfileApp, App, Backbone, Marionette, $, _ )->

        # define the route for the app
        class UserProfileAppRouter extends Marionette.AppRouter

            appRoutes :
                'profile' : 'show'

        #public API
        API =
            show : ->
                params =
                    region : App.mainContentRegion

                App.execute "show:user:profile", params

        #on start of the app call the function based on the routes
        UserProfileApp.on 'start' : ->
            new UserProfileAppRouter
                controller : API

