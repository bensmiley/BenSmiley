#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/add/user-domain-add-view' ], ( App, AppController, View )->

    #start the app module
    App.module "UserDomainAddApp", ( UserDomainAddApp, App, BackBone, Marionette, $, _ )->

        # Controller class for showing user domain list
        class UserDomainAddController extends AppController

            initialize : ( opts )->

                #get user domain layout
                @layout = @getLayout()

                @show @layout

            getLayout : ->
                new View.UserDomainAddView


        #handler for showing the user domain page : triggered from left nav region
        App.commands.setHandler "add:user:domain", ( opts ) ->
            new UserDomainAddController opts


