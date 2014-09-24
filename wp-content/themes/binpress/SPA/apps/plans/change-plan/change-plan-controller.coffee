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
                msgbus.commands.execute "when:fetched", @domainModel, =>
                    @showDomainSubscriptionView()

                #fetch the selected plan details and show the view on successful fetch
                @selectedPlanModel = msgbus.reqres.request "get:plan:by:planid", @planId
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
                @paymentFormView = @getPaymentFormView @userBillingModel
                @layout.paymentViewRegion.show @paymentFormView

                #listen to the card form view click events
                # @listenTo @paymentFormView, 'user:credit:card:details', @newCreditCardPayment
                @listenTo @paymentFormView, 'use:stored:card', @useStoredCreditCard

                @listenTo @paymentFormView, "new:credit:card:payment", @newCardPayment 

            #when user clicks cancel and chooses to use the stored card for payment
            # instead of changing the card
            useStoredCreditCard : ->
                @showPaymentCardView()

            getPaymentFormView : ( userBillingModel )->
                new ChangePlanView.PaymentFormView
                    model : userBillingModel

            #=======================MAHIMA's OLD CODE BEGINS=======================
            #ajax action when user makes payment through card for the first time
            # newCreditCardPayment : ( creditCardData )->
            #     options =
            #         url : AJAXURL
            #         method : "POST"
            #         data :
            #             action : 'user-new-payment'
            #             creditCardData : creditCardData
            #             selectedPlanId : @planId
            #             selectedPlanName : @selectedPlanModel.get 'plan_name'
            #             selectedPlanPrice : @selectedPlanModel.get 'price'
            #             domainId : @domainId
            #             activePlanId : @domainModel.get 'plan_id'
            #             subscriptionId : @domainModel.get 'subscription_id'

            #     $.ajax( options ).done ( response )=>
            #         @paymentFormView.triggerMethod "payment:sucess", response, @domainId
            #=======================MAHIMA's OLD CODE ENDS=======================

            newCardPayment : ( paymentMethodNonce )=>
                # console.log paymentMethodNonce
                # console.log @domainModel.get 'subscription_id'
                options =
                    method : 'POST'
                    url : AJAXURL
                    data :
                        'paymentMethodNonce' : paymentMethodNonce
                        'selectedPlanId' : @planId
                        'customerId' : @userBillingModel.get 'braintree_customer_id'
                        'currentSubscriptionId' : @domainModel.get 'subscription_id'
                        'selectedPlanName' : @selectedPlanModel.get 'plan_name'
                        'selectedPlanPrice' : @selectedPlanModel.get 'price'
                        'domainId' : @domainId
                        'activePlanId' : @domainModel.get 'plan_id'
                        'action' : 'user-new-payment'

                $.ajax( options ).done ( response )=>
                    if response.code == "OK"
                        @paymentFormView.triggerMethod "payment:success", response.msg
                    else
                        @paymentFormView.triggerMethod "payment:error", response.msg

            #ajax action when user makes payment through card for the first time
            creditCardPayment : ( creditCardToken )->
                options =
                    url : AJAXURL
                    method : "POST"
                    data :
                        action : 'user-make-payment'
                        creditCardToken : creditCardToken
                        selectedPlanId : @planId
                        selectedPlanName : @selectedPlanModel.get 'plan_name'
                        selectedPlanPrice : @selectedPlanModel.get 'price'
                        domainId : @domainId
                        activePlanId : @domainModel.get 'plan_id'
                        subscriptionId : @domainModel.get 'subscription_id'

                $.ajax( options ).done ( response )=>
                    @paymentCardView.triggerMethod "payment:sucess", response, @domainId


        #handler for changing the domain plan,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainID : parseInt domainID
        # planID :  planID
        App.commands.setHandler "change:plan", ( opts ) ->
            new ChangePlanController opts



