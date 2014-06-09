#include the files for the app
define [ 'app'
         'regioncontroller'
         'msgbus'
         'apps/user-domains/show/user-domains-view'
         'apps/user-domains/add/user-domain-add-controller'], ( App, AppController, msgbus, View )->

    #start the app module
    App.module "UserDomainApp", ( UserDomainApp, App, BackBone, Marionette, $, _ )->

        # Controller class for showing user domain list
        class UserDomainController extends AppController

            initialize : ( opts )->

                #get user domain layout
                @layout = @getLayout()

                @listenTo @layout, "show", ->
                    userDomainsCollection = msgbus.reqres.request "get:current:user:domains"
                    userDomainsCollection.fetch()
                    @layout.domainListRegion.show @getDomainListView userDomainsCollection

                @listenTo @layout, "add:user:domain:clicked", ->
                    App.execute "add:user:domain", region : @layout.domainListRegion

                @show @layout

            getLayout : ->
                new View.UserDomainView

            getDomainListView :(userDomainsCollection) ->
                new View.DomainListView
                    collection : userDomainsCollection


        #handler for showing the user domain page : triggered from left nav region
        App.commands.setHandler "show:user:domains", ( opts ) ->
            new UserDomainController opts


