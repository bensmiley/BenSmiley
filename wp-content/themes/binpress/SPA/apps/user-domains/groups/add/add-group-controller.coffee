#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/groups/add/add-group-view'
         'msgbus' ], ( App, AppController, AddGroupView, msgbus )->

    #start the app module
    App.module "UserDomainAddGroupApp", ( UserDomainAddEditApp, App, BackBone, Marionette, $, _ )->

        # Controller class for add/edit of user domain
        class UserDomainAddGroupController extends AppController

            initialize : ( opts )->

                @domain_id = opts.domain_id

                @view = @getView @domain_id

                @listenTo @view, "save:domain:group:clicked", @saveDomainGroup

                @show @view

            getView :(domainid) ->
                new AddGroupView
                    domain_id : domainid

            saveDomainGroup : ( groupdata )->
                domainGroupModel = msgbus.reqres.request "create:domain:group:model", groupdata
                domainGroupModel.save null,
                    wait : true
                    success : @domainGroupAdded

            domainGroupAdded:(domainGroupModel)->
                @view.triggerMethod "domain:group:added"
                console.log domainGroupModel


        #handler for showing the add-edit domain page
        App.commands.setHandler "add:domain:groups", ( opts ) ->
            new UserDomainAddGroupController opts



