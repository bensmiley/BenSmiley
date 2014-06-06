#include the files for the app
define ['app'
        'region-controller'
        'apps/upload/upload-view'], (App, AppController, View)->

    #start the app module
    App.module 'UploadApp', (UploadApp, App, Backbone, Marionette, $, _)->

        # Controller class for showing left nav nenu region
        class UploadApp.Controller extends AppController

            # initialize
            initialize : (opts)->
                #get the logged in user model
                @usermodel = opts.model

                #get upload view
                view = @_getView @usermodel

                @show view

            _getView : (usermodel)->
                new View.UploadView
                    model : usermodel


        App.commands.setHandler 'start:upload:app', (options) ->
            new UploadApp.Controller options
