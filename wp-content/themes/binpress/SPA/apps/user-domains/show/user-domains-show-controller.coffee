#include the files for the app
define ['app'
        'region-controller'
        'apps/user-domains/show/user-domains-view'
        'apps/user-domains/add/user-domain-add-controller'], (App, AppController, View)->

    #start the app module
    App.module "UserDomainApp", (UserDomainApp, App, BackBone, Marionette, $, _)->

        # Controller class for showing user domain list
        class UserDomainController extends AppController

            initialize : (opts)->

                #get user domain layout
                @layout = @getLayout()

                @listenTo @layout,"show",->
                    @layout.domainListRegion.show @getDomainListView()

                @listenTo @layout ,"add:user:domain:clicked",->
                    App.execute "add:user:domain" , region : @layout.domainListRegion

                @show @layout

            getLayout : ->
                new View.UserDomainView

            getDomainListView : ->
                new View.DomainListView


        #handler for showing the user domain page : triggered from left nav region
        App.commands.setHandler "show:user:domains", (opts) ->
            new UserDomainController opts


