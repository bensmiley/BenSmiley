#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/edit/domain-edit-view'
         'msgbus'
         'apps/user-domains/groups/add/add-group-controller' ], ( App, RegionController, DomainEditLayout, msgbus )->

    #start the app module
    App.module "UserDomainApp.Edit", ( Edit, App, BackBone, Marionette, $, _ )->

        # Controller class for add/edit of user domain
        class DomainEditController extends RegionController

            initialize : ( opts )->
                @domainId = opts.domainId

                @domainModel = msgbus.reqres.request "get:domain:model:by:id",@domainId
                @domainModel.fetch
                            success : @showEditView

            showEditView:( domainModel )=>

                @layout = @geEditDomainLayout domainModel

                @listenTo @layout, "show", =>

                    #start the add domain group app
                    App.execute "add:domain:groups",
                        region : @layout.addDomainGroupRegion
                        domain_id : @domainId

                    #start the list domain group app
                    App.execute "add:domain:groups",
                        region : @layout.listDomainGroupRegion
                        domain_id : @domainId

                #listen to edit domain click event
                @listenTo @layout, "edit:domain:clicked",@editDomain

                @show @layout

            geEditDomainLayout : ( domainModel ) ->
                new DomainEditLayout
                    model : domainModel

            editDomain : ( domainData )=>
                @domainModel.set domainData
                @domainModel.save null,
                    wait : true
                    success : @domainUpdated

            #trigger sucess msg on successful update
            domainUpdated : =>
                @layout.triggerMethod "domain:updated"


        #handler for edit domain page,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainId : parseInt id
        App.commands.setHandler "edit:user:domain", ( opts ) ->
            new DomainEditController opts



