#include the files for the app
define [ 'app'
         'regioncontroller'
         'msgbus'
         'apps/user-domains/list/user-domains-list-view' ], ( App, RegionController, msgbus, View )->

    #start the app module
    App.module "UserDomainApp.List", ( List, App, BackBone, Marionette, $, _ )->

        # Controller class for showing user domain list
        class UserDomainListController extends RegionController

            initialize : ( opts ) ->

                #get the user domains collection
                @userDomainsCollection = msgbus.reqres.request "get:current:user:domains"

                #on collection fetch show the view
                msgbus.commands.execute "when:fetched", @userDomainsCollection, =>
                    @showDomainListView()

            showDomainListView : ->
                #get the user domain list view
                @domainListView = @getDomainListView @userDomainsCollection

                #show user domain list view
                @show @domainListView,
                    loading : true

            getDomainListView : ( userDomainsCollection ) ->
                new View.DomainListView
                    collection : userDomainsCollection

        #handler for showing the user domain list page,options to be passed
        # region :  App.mainContentRegion
        App.commands.setHandler "list:user:domains", ( opts ) ->
            new UserDomainListController opts


