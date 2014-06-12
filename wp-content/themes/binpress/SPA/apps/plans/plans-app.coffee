#include the files for the app
define [ 'app'
         'msgbus'
         'apps/plans/list/list-plans-controller' ], ( App, msgbus )->

    #start the app module
    App.module 'PlansApp', ( PlansApp, App, Backbone, Marionette, $, _ )->

        # define the route for the app
        class PlansAppRouter extends Marionette.AppRouter

            appRoutes :
                'change-plan' : 'show'

        #public API
        API =
            show : ->
                App.execute "show:plans:list", region : App.mainContentRegion

        #on start of the app call the function based on the routes
        PlansApp.on 'start' : ->
            new PlansAppRouter
                controller : API

