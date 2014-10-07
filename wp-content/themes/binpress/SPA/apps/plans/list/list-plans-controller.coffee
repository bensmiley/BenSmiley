#include the files for the app
define [ 'app'
         'msgbus'
         'regioncontroller'
         'apps/plans/list/list-plan-view' ], ( App, msgbus, RegionController, Views )->

    #start the app module
    App.module "PlansApp.List", ( List, App, BackBone, Marionette, $, _ )->

        # Controller class for listing all plans
        class PlansListController extends RegionController

            initialize : ( opts )->

                # @show new Views.BetaReleaseView opts
                # return

                #get the domain ID from the options passed to controller
                @domainId = opts.domainID

                #feth the currrent subscription for the domain
                @subscriptionModel = msgbus.reqres.request "get:subscription:for:domain", @domainId
                @subscriptionModel.fetch()
                msgbus.commands.execute "when:fetched", @subscriptionModel, =>
                    #get the plan collection to display all plans
                    @getPlanCollection()

                #fetch all the available plans
                @planCollection = msgbus.reqres.request "get:all:plans"
                @planCollection.fetch()

                @show new Marionette.LoadingView

            getPlanCollection : ->
                msgbus.commands.execute "when:fetched", @planCollection, =>
                    @showPlanListView()

            showPlanListView : =>
                planListShowView = @getPlanListView @subscriptionModel

                planListShowView.on 'cancel:paid:subscription', @cancelPaidSubscription
                
                #show the view
                @show planListShowView,
                    loading : true

            getPlanListView : ( subscriptionModel ) ->
                new Views.PlansListView
                    collection : @planCollection
                    model : subscriptionModel
                    domainId : @domainId

            cancelPaidSubscription : ( activeSubscriptionId, domainId ) ->
                console.log "Cancel paidd"
                console.log activeSubscriptionId
                console.log domainId
                options =
                    url : AJAXURL
                    method : "POST"
                    data :
                        action : 'cancel-paid-subscription'
                        activeSubscriptionId : activeSubscriptionId
                        domainId : domainId

                $.ajax( options ).done ( response )=>
                    console.log "Response"
                    console.log response.data

        #handler for showing the plans list page,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainID : Int domain id
        App.commands.setHandler "show:plans:list", ( options ) ->
            new PlansListController options


