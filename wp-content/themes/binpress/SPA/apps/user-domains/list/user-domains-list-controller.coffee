#include the files for the app
define [ 'app'
         'regioncontroller'
         'msgbus'
         'apps/user-domains/list/user-domains-list-view' ], ( App, RegionController, msgbus, View )->

    #start the app module
    App.module "UserDomainApp.List", ( List, App, BackBone, Marionette, $, _ )->

        # Controller class for showing user domain list
        class UserDomainListController extends RegionController

            initialize : ->

                #get the user domains collection
                @userDomainsCollection = msgbus.reqres.request "get:current:user:domains"

                #on collection fetch show the view
                msgbus.commands.execute "when:fetched", @userDomainsCollection, =>
                    @showDomainListView()

            showDomainListView : ->
                #get the user domain list view
                @domainListView = @getDomainListView @userDomainsCollection

                #listen to click events
                @listenTo @domainListView, "itemview:delete:domain:clicked", @deleteDomainClick

                #show user domain list view
                @show @domainListView

            getDomainListView : ( userDomainsCollection ) ->
                new View.DomainListView
                    collection : userDomainsCollection

            deleteDomainClick : ( iv, model )->
                model.destroy
                    allData : false
                    wait : true
                    success : @domainDeleted

            domainDeleted : =>
                @domainListView.triggerMethod "domain:deleted"

        #handler for showing the user domain list page,options to be passed
        # region :  App.mainContentRegion
        App.commands.setHandler "list:user:domains", ->
            new UserDomainListController


