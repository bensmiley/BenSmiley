#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/groups/add/add-group-view'
         'msgbus' ], ( App, RegionController, AddGroupView, msgbus )->

    #start the app module
    App.module "UserDomainApp.AddGroups", ( AddGroups, App, BackBone, Marionette, $, _ )->

        # Controller class for adding domain groups
        class DomainAddGroupController extends RegionController

            initialize : ( opts )->

                @domain_id = opts.domain_id

                @view = @getView @domain_id

                @listenTo @view, "save:domain:group:clicked", @saveDomainGroup

                @show @view,
                    loading : true

            getView : ( domainid ) ->
                new AddGroupView
                    domain_id : domainid

            saveDomainGroup : ( groupdata )->
                domainGroupModel = msgbus.reqres.request "create:domain:group:model", groupdata
                domainGroupModel.save null,
                    wait : true
                    success : @domainGroupAdded

            domainGroupAdded : ( domainGroupModel )->
                @view.triggerMethod "domain:group:added"
                console.log domainGroupModel


        #handler for showing the add domain group section:
        # This section is nested inside the edit domain page view
        # options passed:
        # region : layout.addDomainGroupRegion (from edit domain view)
        # domain_id : int domainId
        App.commands.setHandler "add:domain:groups", ( opts ) ->
            new DomainAddGroupController opts



