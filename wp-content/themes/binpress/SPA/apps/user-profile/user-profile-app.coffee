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
                'profile/:id' : 'show'

        #public API
        API =
            show : ( id )->
                params =
                    region : App.mainContentRegion

                if not _.isNull id
                    params['userId'] = parseInt id
                else
                    params['userId'] = msgbus.reqres.request "get:current:user:id"

                App.execute "show:user:profile", params

        #on start of the app call the function based on the routes
        UserProfileApp.on 'start' : ->
            new UserProfileAppRouter
                controller : API

