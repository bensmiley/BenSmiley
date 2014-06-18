#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/upload/upload-view' ], ( App, RegionController, UploadView )->

    #start the app module
    App.module 'UploadApp', ( UploadApp, App, Backbone, Marionette, $, _ )->

        # Controller class to start the upload app
        class UploadApp.Controller extends RegionController

            # initialize
            initialize : ( opts )->
                #get the logged in user model
                @userModel = opts.model

                #get upload view
                view = @_getView @userModel

                @show view

            # gets the main upload view
            _getView : ( userModel ) ->
                new UploadView
                    model : userModel

        App.commands.setHandler 'start:upload:app', ( options ) ->
            new UploadApp.Controller options

