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
                #get the domain ID and plan ID from the options passed to controller
                @domainId = opts.domainID
                @planId = opts.planID

                #get the layout of change plan
                @layout = @getLayout()

                #show the layout
                @show @layout,
                    loading : true

                #fetch the active subscription and show the view on successdul fetch
                @subscriptionModel = msgbus.reqres.request "get:subscription:for:domain", @domainId
                @subscriptionModel.fetch()
                msgbus.commands.execute "when:fetched", @subscriptionModel, =>
                    @showActiveSubscriptionView()

                #fetch the selected plan details and show the view on successful fetch
                @selectedPlanModel = msgbus.reqres.request "get:plan:by:planid", @planId
                @selectedPlanModel.fetch()
                msgbus.commands.execute "when:fetched", @selectedPlanModel, =>
                    @showSelectedPlanView()

                #fetch the user billing data and show the payment view on successful fetch
                @userBillingModel = msgbus.reqres.request "get:user:billing:data"
                @userBillingModel.fetch()
                msgbus.commands.execute "when:fetched", @userBillingModel, =>
                    @showPaymentView()


            getActiveSubscriptionView : ( subscriptionModel ) ->
                new ChangePlanView.ActiveSubscriptionView
                    model : subscriptionModel

            showActiveSubscriptionView : =>
                activeSubscriptionView = @getActiveSubscriptionView @subscriptionModel
                @layout.activeSubscriptionRegion.show activeSubscriptionView

            getSelectedPlanViewView : ( selectedPlanModel ) ->
                new ChangePlanView.SelectedPlanView
                    model : selectedPlanModel

            showSelectedPlanView : =>
                selectedPlanView = @getSelectedPlanViewView @selectedPlanModel
                @layout.selectedPlanRegion.show selectedPlanView

            getLayout : ->
                new ChangePlanView.ChangePlanLayout

            showPaymentView : =>
                #check if user has credit card info
                cardExists = @userBillingModel.get 'card_exists'

                if cardExists
                    @showPaymentCardView()
                else
                    @showPaymentFormView()

            #view shown if user has credit card info stored
            showPaymentCardView : =>
                paymentCardView = @getPaymentCardView @userBillingModel
                @layout.paymentViewRegion.show paymentCardView

            getPaymentCardView : ( userBillingModel )->
                new ChangePlanView.PaymentCardView
                    model : userBillingModel

            #view shown if user does not have credit card info stored
            showPaymentFormView : =>
                paymentFormView = @getPaymentFormView @userBillingModel
                @layout.paymentViewRegion.show paymentFormView

            getPaymentFormView : ( userBillingModel )->
                new ChangePlanView.PaymentFormView
                    model : userBillingModel


        #handler for changing the domain plan,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainID : parseInt domainID
        # planID :  planID
        App.commands.setHandler "change:plan", ( opts ) ->
            new ChangePlanController opts



