#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/plans/change-plan/change-plan-view'
         'msgbus' ], ( App, RegionController, ChangePlanView, msgbus )->

    #start the app module
    App.module "PlansApp.Change", ( Change, App, BackBone, Marionette, $, _ )->

        # Controller class for changing the plan
        class ChangePlanController extends RegionController

            initialize : ( opts )->
                @domainId = opts.domainID

                subscriptionModel = msgbus.reqres.request "get:subscription:for:domain", @domainId
                subscriptionModel.fetch
                    success : @changePlan

#                @layout = @getLayout()
#
#                @show @layout,
#                    loading : true
#
#                #fetch the current subscriptionfor the domain, on sucess
#                #load the active subscription view
#                @subscriptionModel = msgbus.reqres.request "get:subscription:for:domain", @domainId
#                @subscriptionModel.fetch
#                    success : @showActiveSubscription

#            getActiveSubscriptionView : ( subscriptionModel ) ->
#                new ChangePlanView.ActiveSubscriptionView
#                    model : subscriptionModel
#
#            showActiveSubscription : ( subscriptionModel )=>
#                activeSubscriptionView = @getActiveSubscriptionView subscriptionModel
#                @layout.activeSubscriptionRegion.show activeSubscriptionView

            changePlan : ( subscriptionModel ) =>
                changePlanView = @getChangePlanView subscriptionModel
                @show changePlanView,
                    loading : true

            getChangePlanView : ( subscriptionModel ) ->
                new ChangePlanView
                    model : subscriptionModel


        #handler for changing the domain plan,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainID : parseInt domainID
        # planID :  planID
        App.commands.setHandler "change:plan", ( opts ) ->
            new ChangePlanController opts



