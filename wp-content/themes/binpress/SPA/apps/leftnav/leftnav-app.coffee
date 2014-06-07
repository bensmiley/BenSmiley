#include the files for the app
define ['app','apps/leftnav/show/leftnav-controller'], (App)->

    #define the app module
    App.module 'LeftNavApp', (LeftNavApp, App, Backbone, Marionette, $, _)->
        leftnavController = null

        #PUBLIC API
        API =
        # show the left nav region
            show : ->
                leftnavController = new LeftNavApp.Show.Controller
                    region : App.leftNavRegion

        # show the region on start
        LeftNavApp.on 'start', ->
            API.show()

