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

                #show loaders initally in the layout regions
                @listenTo @layout, "show", ->
                    @layout.selectedPlanRegion.show new Marionette.LoadingView
                    @layout.domainSubscriptionRegion.show new Marionette.LoadingView
                    @layout.paymentViewRegion.show new Marionette.LoadingView

                #show the layout
                @show @layout,
                    loading : true

                #fetch the active subscription and show the view on successdul fetch
                @domainModel = msgbus.reqres.request "get:domain:model:by:id", @domainId
                @domainModel.fetch()
                msgbus.commands.execute "when:fetched", @domainModel, =>
                    @showDomainSubscriptionView()

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


            getDomainSubscriptionView : ( domainModel ) ->
                new ChangePlanView.DomainSubscriptionView
                    model : domainModel

            showDomainSubscriptionView : =>
                domainSubscriptionView = @getDomainSubscriptionView @domainModel
                @layout.domainSubscriptionRegion.show domainSubscriptionView

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
                @paymentCardView = @getPaymentCardView @userBillingModel
                @layout.paymentViewRegion.show @paymentCardView

                #listen to the card submit event of the view
                @listenTo @paymentCardView, 'user:card:payment', @creditCardPayment
                @listenTo @paymentCardView, 'change:card:clicked', @showPaymentFormView


            getPaymentCardView : ( userBillingModel )->
                new ChangePlanView.PaymentCardView
                    model : userBillingModel

            #view shown if user does not have credit card info stored
            showPaymentFormView : =>
                console.log @userBillingModel
                @paymentFormView = @getPaymentFormView @userBillingModel
                @layout.paymentViewRegion.show @paymentFormView

                #listen to the card submit event of the view
                @listenTo @paymentFormView, 'user:credit:card:details', @newCreditCardPayment

            getPaymentFormView : ( userBillingModel )->
                new ChangePlanView.PaymentFormView
                    model : userBillingModel

            #ajax action when user makes payment through card for the first time
            newCreditCardPayment : ( creditCardData )->
                options =
                    url: AJAXURL
                    method: "POST"
                    data:
                        action : 'user-new-payment'
                        creditCardData : creditCardData
                        planId : @selectedPlanModel.get 'plan_id'
                        domainId : @domainId

                $.ajax(options).done (response)=>
                    @paymentFormView.triggerMethod "payment:sucess",response,@domainId

            #ajax action when user makes payment through card for the first time
            creditCardPayment : ( creditCardToken )->
                options =
                    url: AJAXURL
                    method: "POST"
                    data:
                        action : 'user-make-payment'
                        creditCardToken : creditCardToken
                        planId : @selectedPlanModel.get 'plan_id'
                        domainId : @domainId

                $.ajax(options).done (response)=>
                    @paymentCardView.triggerMethod "payment:sucess",response,@domainId


        #handler for changing the domain plan,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainID : parseInt domainID
        # planID :  planID
        App.commands.setHandler "change:plan", ( opts ) ->
            new ChangePlanController opts



