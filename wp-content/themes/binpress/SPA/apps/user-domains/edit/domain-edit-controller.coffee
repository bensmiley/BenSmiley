#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/edit/domain-edit-view'
         'msgbus'
         'apps/user-domains/groups/add/add-group-controller'
         'apps/user-domains/groups/list/list-group-controller' ], ( App, RegionController, EditDomainView, msgbus )->

    #start the app module
    App.module "UserDomainApp.Edit", ( Edit, App, BackBone, Marionette, $, _ )->

        # Controller class for add/edit of user domain
        class DomainEditController extends RegionController

            initialize : ( opts )->
                #get domainId from controller options
                @domainId = opts.domainId

                #fetch the domain model and on success show the edit domain view
                @domainModel = msgbus.reqres.request "get:domain:model:by:id", @domainId
                @domainModel.fetch
                    success : @showEditView

            showEditView : ( domainModel )=>
                #get the edit domain layout
                @layout = @getEditDomainLayout domainModel

                @listenTo @layout, "show", =>

                    #start the add domain group app
                    App.execute "add:domain:groups",
                        region : @layout.addDomainGroupRegion
                        domain_id : @domainId

                    #start the list domain group app
                    App.execute "list:domain:groups",
                        region : @layout.listDomainGroupRegion
                        domain_id : @domainId

                    #fetch the current subscriptionfor the domain, on sucess
                    #load the active subscription view
                    @subscriptionModel = msgbus.reqres.request "get:subscription:for:domain", @domainId
#                    @showActiveSubscription @subscriptionModel
                    @subscriptionModel.fetch
                        success : @showActiveSubscription

                #listen to edit domain click event
                @listenTo @layout, "edit:domain:clicked", @editDomain

                #show the edit domain layout
                @show @layout,
                    loading : true
                    entities : @subscriptionModel

            getEditDomainLayout : ( domainModel ) ->
                new EditDomainView.DomainEditLayout
                    model : domainModel

            getActiveSubscriptionView : ( subscriptionModel ) ->
                new EditDomainView.ActiveSubscriptionView
                    model : subscriptionModel

            showActiveSubscription : ( subscriptionModel )=>
                activeSubscriptionView = @getActiveSubscriptionView subscriptionModel
                @layout.activeSubscriptionRegion.show activeSubscriptionView

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



