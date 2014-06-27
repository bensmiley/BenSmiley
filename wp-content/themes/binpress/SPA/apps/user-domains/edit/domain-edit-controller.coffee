#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/edit/domain-edit-view'
         'msgbus'
         'apps/user-domains/groups/show/show-group-controller' ], ( App, RegionController, EditDomainView, msgbus )->

    #start the app module
    App.module "UserDomainApp.Edit", ( Edit, App, BackBone, Marionette, $, _ )->

        # Controller class for add/edit of user domain
        class DomainEditController extends RegionController

            initialize : ( opts )->
                #get domainId from controller options
                @domainId = opts.domainId

                #fetch the domain model and show layout
                @domainModel = msgbus.reqres.request "get:domain:model:by:id", @domainId

                #on successful fetch show the edit domain layout
                msgbus.commands.execute "when:fetched", @domainModel, ->
                @showEditView @domainModel

                #fetch the current subscription model for the domain
                @subscriptionModel = msgbus.reqres.request "get:subscription:for:domain", @domainId
                @subscriptionModel.fetch()


            showEditView : ( domainModel )=>
                #get the edit domain layout
                @layout = @getEditDomainLayout domainModel

                @listenTo @layout, "show", =>

                    #show loading view till regions of layout are fetched
                    @layout.activeSubscriptionRegion.show new Marionette.LoadingView
                    @layout.groupsRegion.show new Marionette.LoadingView

                    #start the domain group app
                    App.execute "show:domain:groups",
                        region : @layout.groupsRegion
                        domain_id : @domainId

                    #on successful fetch show the active subscription view
                    msgbus.commands.execute "when:fetched", @subscriptionModel, =>
                        @showActiveSubscription @subscriptionModel

                #listen to edit domain click event
                @listenTo @layout, "edit:domain:clicked", @editDomain

                #listen to edit domain click event
                @listenTo @layout, "delete:domain:clicked", @deleteDomain

                #show the edit domain layout
                @show @layout,
                    loading : true

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
            domainUpdated : ( userDomain ) =>
                userDomainCollection = msgbus.reqres.request "get:current:user:domains"
                userDomainCollection.add userDomain
                @layout.triggerMethod "domain:updated"

            deleteDomain : ->
                @domainModel.destroy
                    allData : false
                    wait : true
                    success : @domainDeleted

            domainDeleted : ->
                #redirect the page to domain list
                mainUrl = window.location.href.replace Backbone.history.getFragment(), ''
                redirect_url = "#{mainUrl}#domains"
                window.location.href = redirect_url

        #handler for edit domain page,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainId : parseInt id
        App.commands.setHandler "edit:user:domain", ( opts ) ->
            new DomainEditController opts



