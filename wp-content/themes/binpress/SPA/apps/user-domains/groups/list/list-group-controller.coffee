#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/groups/list/list-group-view'
         'msgbus' ], ( App, RegionController, ListGroupView, msgbus )->

    #start the app module
    App.module "UserDomainApp.ListGroups", ( ListGroups, App, BackBone, Marionette, $, _ )->

        # Controller class for listing domain groups
        class DomainListGroupController extends RegionController

            initialize : ( opts )->

                @domainId = opts.domain_id

                #get the groups for the domain
                @groupCollection = msgbus.reqres.request "get:groups:for:domains",@domainId
                @groupCollection.fetch
                            data :
                                'domain_id' : @domainId

                @view = @getView @domain_id

#                @listenTo @view, "save:domain:group:clicked", @saveDomainGroup

                @show @view,
                    loading : true

            getView : ( domainid ) ->
                new ListGroupView
                    domain_id : domainid

#            saveDomainGroup : ( groupdata )->
#                domainGroupModel = msgbus.reqres.request "create:domain:group:model", groupdata
#                domainGroupModel.save null,
#                    wait : true
#                    success : @domainGroupAdded
#
#            domainGroupAdded : ( domainGroupModel )->
#                @view.triggerMethod "domain:group:added"
#                console.log domainGroupModel


        #handler for showing the add domain group section:
        # This section is nested inside the edit domain page view
        # options passed:
        # region : layout.listDomainGroupRegion (from edit domain view)
        # domain_id : int domainId
        App.commands.setHandler "list:domain:groups", ( opts ) ->
            new DomainListGroupController opts



