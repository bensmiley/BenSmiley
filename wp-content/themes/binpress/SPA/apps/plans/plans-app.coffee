#include the files for the app
define [ 'app'
         'msgbus'
         'apps/plans/list/list-plans-controller'
         'apps/plans/change-plan/change-plan-controller'], ( App, msgbus )->

    #start the app module
    App.module 'PlansApp', ( PlansApp, App, Backbone, Marionette, $, _ )->

        # define the route for the app
        class PlansAppRouter extends Marionette.AppRouter

            appRoutes :
                'domains/edit/:domainID/list-plan/:planID' : 'show'
                'domains/edit/:domainID/change-plan/:planID' : 'change'

        #public API
        API =
            show : (domainID , planID ) ->
                App.execute "show:plans:list",
                    region : App.mainContentRegion
                    domainID : parseInt domainID
                    planID : parseInt planID

            change : (domainID , planID ) ->
                App.execute "change:plan",
                    region : App.mainContentRegion
                    domainID : parseInt domainID
                    planID : planID

        #on start of the app call the function based on the routes
        PlansApp.on 'start' : ->
            new PlansAppRouter
                controller : API

