#include the files for the app
define [ 'app'
         'msgbus'
         'regioncontroller'
         'apps/plans/list/list-plan-view' ], ( App, msgbus, RegionController, PlanListView )->

    #start the app module
    App.module "PlansApp.List", ( List, App, BackBone, Marionette, $, _ )->

        # Controller class for listing all plans
        class PlansListController extends RegionController

            initialize : ( opts )->
                #get the plan and domain IDs
                @domainId = opts.domainID

                #get plan list layout
                @layout = @getLayout()

                @listenTo @layout, "show", @showViews

                #show the layout
                @show @layout,
                    loading : true

            getLayout : ->
                new PlanListView.PlansListLayout

            showViews : =>
                @showActiveSubscription()
                @showPlansList()


            showActiveSubscription : =>
                @subscriptionModel = msgbus.reqres.request "get:subscription:for:domain", @domainId
                @subscriptionModel.fetch
                    success : @showActiveSubscriptionView

            showActiveSubscriptionView : ( subscriptionModel ) =>
                activeSubscriptionView = @getActiveSubscriptionView subscriptionModel
                @layout.activeSubscriptionRegion.show activeSubscriptionView

            getActiveSubscriptionView : ( subscriptionModel )->
                new PlanListView.ActiveSubscriptionView
                    model : subscriptionModel

            showPlansList : =>
                @planCollection = msgbus.reqres.request "get:all:plans"
                @planCollection.fetch
                    success : @showPlanListView

            showPlanListView : ( planCollection ) =>
                @planListShowView = @getPlanListView planCollection
                @layout.plansListRegion.show @planListShowView

            getPlanListView : ( planCollection )->
                new PlanListView.PlanListsView
                    collection : planCollection
                    domainId : @domainId


        #handler for showing the plans list page,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainID : Int domain id
        App.commands.setHandler "show:plans:list", ( options ) ->
            new PlansListController options


