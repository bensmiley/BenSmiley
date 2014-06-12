#include the files for the app
define [ 'app'
         'msgbus'
         'regioncontroller'
         'apps/payment/show/payment-show-view' ], ( App, msgbus, RegionController, PaymentView )->

    #start the app module
    App.module "PaymentApp.Show", ( Show, App, BackBone, Marionette, $, _ )->

        # Controller class for payment page
        class PaymentController extends RegionController

            initialize : ( opts )->

                #get payment page layout
                @layout = @getLayout()

                @listenTo @layout, "show", @showPaymentForm

                #show the layout
                @show @layout

            getLayout : ->
                new PaymentView.PaymentLayout

            showPaymentForm : ->
                paymentFormView = new PaymentView.PaymentFormView
                @layout.displayPaymentRegion.show paymentFormView

        App.commands.setHandler "show:payment:page", ( options ) ->
            new PaymentController options


