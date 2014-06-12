#include the files for the app
define [ 'app'
         'regioncontroller'
         'msgbus'
         'apps/user-domains/list/user-domains-list-view'
         'apps/user-domains/add-edit/user-domain-add-edit-controller' ], ( App, RegionController, msgbus, View )->

    #start the app module
    App.module "UserDomainApp.List", ( List, App, BackBone, Marionette, $, _ )->

        # Controller class for showing user domain list
        class UserDomainListController extends RegionController

            initialize : ( opts )->

                #get user domain list layout
                @layout = @getLayout()

                #listen to show events of the layout
                @listenTo @layout, "show", =>
                    #get the user domains collection
                    @userDomainsCollection = msgbus.reqres.request "get:current:user:domains"
                    @userDomainsCollection.fetch()

                    #get the user domain list view
                    @domainListView = @getDomainListView @userDomainsCollection
                    @layout.domainViewRegion.show @domainListView

                    #listen to click events
                    @listenTo @domainListView, "itemview:edit:domain:clicked", @editDomainClick
                    @listenTo @domainListView, "itemview:delete:domain:clicked", @deleteDomainClick
                    @listenTo @domainListView, "add:user:domain:clicked", ->
                        App.execute "add:edit:user:domain", region : @layout.domainViewRegion

                @show @layout

            getLayout : ->
                new View.UserDomainView

            getDomainListView : ( userDomainsCollection ) ->
                new View.DomainListView
                    collection : userDomainsCollection

            editDomainClick : ( iv, model )->
                App.execute "add:edit:user:domain",
                    region : @layout.domainViewRegion
                    model : model

            deleteDomainClick : ( iv, model )->
                model.destroy
                    allData : false
                    wait : true

        #handler for showing the user domain list page
        App.commands.setHandler "list:user:domains", ( opts ) ->
            new UserDomainListController opts


