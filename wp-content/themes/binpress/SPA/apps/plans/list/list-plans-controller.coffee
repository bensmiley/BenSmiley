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
                @planId = opts.planID
                @domainId = opts.domainID

                #get plan list layout
                @layout = @getLayout()

                @listenTo @layout, "show", @showCurrentPlan
                @listenTo @layout, "show", @showPlansList

                #show the layout
                @show @layout

            getLayout : ->
                new PlanListView.PlansListLayout

            showCurrentPlan : =>
                @planModel = msgbus.reqres.request "get:plan:by:id"
                @planModel.fetch
                    data :
                        'domain_id' : @domainId
                        'plan_id' : @planId
                        'action' : 'read-plan'
                    success : @showCurrentPlanView

            showCurrentPlanView : =>
                currentPlanView = @getCurrentPlanView @planModel
                @layout.currentPlanRegion.show currentPlanView

            getCurrentPlanView : ( planModel )->
                new PlanListView.CurrentPlanView
                    model : planModel

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


        #handler for showing the plans list page,options to be passed to controller are:
        # region :  App.mainContentRegion
        # planID : int plan id
        # domainID : Int domain id
        App.commands.setHandler "show:plans:list", ( options ) ->
            new PlansListController options


