#include the files for the app
define [ 'app'
         'msgbus'
         'apps/user-domains/list/user-domains-list-controller' ], ( App, msgbus )->

    #start the app module
    App.module 'UserDomainApp', ( UserDomainApp, App, Backbone, Marionette, $, _ )->

        # define the route for the app
        class UserDomainAppRouter extends Marionette.AppRouter

            appRoutes :
                'domains' : 'list'

        #public API
        API =
            list : ->
                App.execute "list:user:domains", region : App.mainContentRegion

        #on start of the app call the function based on the routes
        UserDomainApp.on 'start' : ->
            new UserDomainAppRouter
                controller : API

