#include the files for the app
define [ 'app'
         'apps/header/show/header-controller' ], ( App )->

    #define the app module
    App.module 'HeaderApp', ( HeaderApp, App, Backbone, Marionette, $, _ )->
        headerController = null

        #PUBLIC API
        API =
        # show the header region
            show : ->
                headerController = new HeaderApp.Show.Controller
                    region : App.headerRegion

        # show the region on start
        HeaderApp.on 'start', ->
            API.show()


