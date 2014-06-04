#include the files for the app
define ['app'
        'region-controller'
        'apps/upload/upload-view'], (App, AppController,View)->

    #start the app module
    App.module 'UploadApp', (UploadApp, App, Backbone, Marionette, $, _)->

        # Controller class for showing left nav nenu region
        class UploadApp.Controller extends AppController

            # initialize
            initialize: (opts)->
                view = @_getView()
                @show view

            # gets the main login view
            _getView: (mediaCollection)->
                new View.UploadView


        App.commands.setHandler 'start:upload:app', (options) =>
            new UploadApp.Controller
                region: options.region
