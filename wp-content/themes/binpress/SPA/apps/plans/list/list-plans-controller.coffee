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
                #get the domain IDs
                @domainId = opts.domainID

                subscriptionModel = msgbus.reqres.request "get:subscription:for:domain", @domainId
                subscriptionModel.fetch
                    success : @getPlanCollection
                @show new Marionette.LoadingView

            getPlanCollection : ( subscriptionModel ) =>
                @subscriptionModel = subscriptionModel
                @planCollection = msgbus.reqres.request "get:all:plans"
                @planCollection.fetch
                    success : @showPlanListView

            showPlanListView : ( planCollection ) =>
                planListShowView = @getPlanListView planCollection
                #show the view
                @show planListShowView,
                    loading : true

            getPlanListView : ( planCollection ) ->
                new PlanListView
                    collection : planCollection
                    model : @subscriptionModel


        #handler for showing the plans list page,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainID : Int domain id
        App.commands.setHandler "show:plans:list", ( options ) ->
            new PlansListController options


