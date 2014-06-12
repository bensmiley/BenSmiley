#include the files for the app
define [ 'app'
         'msgbus'
         'regioncontroller'
         'apps/payment/show/payment-show-view'
         'braintree'], ( App, msgbus, RegionController, PaymentView ,Braintree )->

    #start the app module
    App.module "PaymentApp.Show", ( Show, App, BackBone, Marionette, $, _ )->

        # Controller class for payment page
        class PaymentController extends RegionController

            initialize : ( opts )->

                #get payment page layout
                @layout = @getLayout()

                @listenTo @layout ,"show",@showPaymentForm

                #show the layout
                @show @layout

            getLayout : () ->
                new PaymentView.PaymentLayout

            showPaymentForm:=>
                paymentFormView = new PaymentView.PaymentFormView
                @layout.displayPaymentRegion.show paymentFormView
                @listenTo paymentFormView,"make:payment:click",@makePayment

            makePayment:(userData)->
                #@ajaxCall userData
#                @data = userData
#                ajax_submit  = ->
#                        ajaxAction = AJAXURL+'?action=make-user-payment'
#                        $.post ajaxAction,@data,(response)->
#                            console.log response
#
#
#                braintree = Braintree.create("MIIBCgKCAQEA0fQXY7zHRl2PSEoZGOWDseI9MTDz2eO45C5M27KhN/HJXqi7sj8UDybrZJdsK+QL4Cw55r285Eeka+a5tAciEqd3E6YXkNokVmgo6/Wg21vYJKRvcnLkPE+J5iBFfQBBEMNKZMALl1P7HHkfOJsFZNO9+YOfiE+wl0QC8SnjZApftJ69ibbuFdFSR3L4kP6tZSQWeJS9WnkDzxGvRUyGFfs26x/q7Kxn+hdXkxTDd1o8FhjTCP/EkmHxhhJyYgzagtbJ84nxaLBuz6yW8bx5Qwt1ZiWUVVUIJlMiQtXUP05CId+aMIV8wX3OWtyAmTpn8N++tXYGjt/kY/bf8oY3yQIDAQAB");
#                braintree.onSubmitEncryptForm('payment-form', ajax_submit);


#            ajaxCall:(userData)->
#                options =
#                    url: AJAXURL,
#                    method: 'POST',
#                    data:
#                        action: 'make-user-payment'
#                        userData: userData
#
#                $.ajax(options).done (response)->
#                    console.log response
#                .fail (resp)->
#                        console.log response



        App.commands.setHandler "show:payment:page", ( options ) ->
            new PaymentController options


