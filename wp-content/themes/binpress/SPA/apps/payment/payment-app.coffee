#include the files for the app
define [ 'app'
         'msgbus'
         'apps/payment/show/payment-show-controller' ], ( App, msgbus )->

    #start the app module
    App.module 'PaymentApp', ( PaymentApp, App, Backbone, Marionette, $, _ )->

        # define the route for the app
        class PaymentAppRouter extends Marionette.AppRouter

            appRoutes :
                'payment' : 'show'

        #public API
        API =
            show : ()->
                App.execute "show:payment:page", region : App.mainContentRegion

        #on start of the app call the function based on the routes
        PaymentApp.on 'start' : ->
            new PaymentAppRouter
                controller : API

