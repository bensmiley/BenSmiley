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

                @domainId = opts.domain_id

                @view = @getView @domainId

                @listenTo @view, "save:domain:group:clicked", @saveDomainGroup

                @show @view,
                    loading : true

            getView : ( domainId ) ->
                new AddGroupView
                    domainId : domainId

            saveDomainGroup : ( groupData )->
                domainGroupModel = msgbus.reqres.request "create:domain:group:model", groupData
                domainGroupModel.save null,
                    wait : true
                    success : @domainGroupAdded

            domainGroupAdded : ( domainGroupModel )=>
                @view.triggerMethod "domain:group:added"


        #handler for showing the add domain group section:
        # This section is nested inside the edit domain page view
        # options passed:
        # region : layout.addDomainGroupRegion (from edit domain view)
        # domain_id : int domainId
        App.commands.setHandler "add:domain:groups", ( opts ) ->
            new DomainAddGroupController opts



