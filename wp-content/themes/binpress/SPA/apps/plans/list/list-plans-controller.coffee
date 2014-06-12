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

                #get plan list layout
                @layout = @getLayout()

                @listenTo @layout ,"show",@showCurrentPlan
                @listenTo @layout ,"show",@showPlansList

                #show the layout
                @show @layout

            getLayout : () ->
                new PlanListView.PlansListLayout

            showCurrentPlan:->
                currentPlanView = new PlanListView.CurrentPlanView
                @layout.currentPlanRegion.show currentPlanView

            showPlansList:->
                planListView = new PlanListView.PlanListView
                @layout.plansListRegion.show planListView



        App.commands.setHandler "show:plans:list", ( options ) ->
            new PlansListController options


