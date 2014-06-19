#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/groups/show/show-group-view'
         'msgbus' ], ( App, RegionController, ShowGroupView, msgbus )->

    #start the app module
    App.module "UserDomainApp.ShowGroups", ( ShowGroups, App, BackBone, Marionette, $, _ )->

        # Controller class for listing domain groups
        class ShowGroupController extends RegionController

            initialize : ( opts )->
                #get the domain Id from the options passed to controller
                @domainId = opts.domain_id

                #get the groups collection for the domain
                @groupCollection = msgbus.reqres.request "get:groups:for:domains"
                @groupCollection.fetch
                    data :
                        'domain_id' : @domainId

                #when the collection is fetched show the view
                msgbus.commands.execute "when:fetched", @groupCollection, =>
                    @onGroupCollectionFetched @groupCollection

            onGroupCollectionFetched : ( groupCollection )=>
                #get the groups view
                @view = @getView groupCollection

                #listen to edit a group event
                @listenTo @view, "itemview:edit:group:clicked", @editGroup

                #listen to save group event
                @listenTo @view, "save:domain:group:clicked", @saveDomainGroup
                @listenTo @view, "update:domain:group:clicked", @updateDomainGroup

                @show @view,
                    loading : true

            getView : ( groupCollection ) =>
                new ShowGroupView
                    collection : groupCollection
                    domainId : @domainId

            saveDomainGroup : ( groupData )->
                #create a group model and save it
                domainGroupModel = msgbus.reqres.request "create:domain:group:model", groupData
                domainGroupModel.save null,
                    wait : true
                    success : @domainGroupAdded

            domainGroupAdded : ( domainGroupModel )=>
                #add the newly createdmodel to the group collection
                @groupCollection.add domainGroupModel
                @view.triggerMethod "domain:group:added"

            editGroup : ( itemview, model )=>
                @editModel = model
                group_name =  model.get 'group_name'
                group_description =  model.get 'group_description'
                @view.triggerMethod "edit:group", group_name,group_description

            updateDomainGroup : ( groupData )=>
                @editModel.set groupData
                @editModel.save null,
                    wait : true
                    success : @groupUpdated

            groupUpdated :=>
                @view.triggerMethod "group:updated"


        #handler for showing the add domain group section:
        # This section is nested inside the edit domain page view
        # options passed:
        # region : layout.groupsRegion (from edit domain view)
        # domain_id : int domainId
        App.commands.setHandler "show:domain:groups", ( opts ) ->
            new ShowGroupController opts



