#include the files for the app
define [ 'marionette'
         'text!apps/plans/templates/changePlanLayout.html'
         'text!apps/payment/templates/paymentForm.html'
         'braintree'
         'card' ], ( Marionette, changePlanTpl, paymentFormTpl, BrainTree, card )->


    # Payment page main layout
    class ChangePlanLayout extends Marionette.Layout

        template : changePlanTpl

        regions :
            activeSubscriptionRegion : '#active-subscription'
            selectedPlanRegion : '#selected-plan'
            paymentViewRegion : '#payment-form'

    #view to show the active subscription
    class ActiveSubscriptionView extends Marionette.ItemView
        template : ' <div class="col-md-3">
                                        <div class="tiles-body">
                                            <div > ACTIVE PLAN </div>
                                            <div class="heading">
                                                <span class="animate-number" >{{plan_name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="tiles-body">
                                            <div > ACTIVE SINCE </div>
                                            <div class="heading">
                                                <span class="animate-number" >{{start_date}}</span>
                                            </div>
                                        </div>
                                    </div>'

        className : 'row'

    #view to show the selected plan
    class SelectedPlanView extends Marionette.ItemView
        template : '<h4 class="semi-bold">Selected plan</h3>

                            <div class="grid simple">
                                <h3 class="bold text-center">{{plan_name}}<br>
                                    <small class="text-danger"> Rs.{{price}}/month</small>
                                </h2>
                                <hr>

                                <ul class="list-unstyled text-center">
                                    <li>Multiple Email Accounts</li>
                                    <li>99.9% Uptime</li>
                                    <li>Enterprise Level Storage</li>
                                    <li>Fully Managed VPS</li>
                                    <li>Reliable 24/7/365 Support</li>
                                    <li>Enterprise Level Storage</li>
                                    <li>Fully Managed VPS</li>
                                    <li>Reliable 24/7/365 Support</li>
                                </ul>
        						<p class="text-danger">Note:</p>
        						<p class="text-muted">Any change of plans in the midddle of cycle
                             will be applicable from new cycle</p>
                            </div>'

        className : 'alert alert-info'

    #view to show the credit card info stored for the user for billing
    class PaymentCardView extends Marionette.ItemView

        template : '<div class="well well-large" style="background-color: #E4E4E4;">
                                        <h3><span class="semi-bold">Card Details</span></h3>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <B>Card Name</B>

                                                    <h3>{{customer_name}}</h3>
                                                </div>
                                                <div class="col-md-4">
                                                    <B>Card Number</B>

                                                    <h3>{{card_number}}</h3>
                                                </div>
                                                <div class="col-md-2">
                                                    <B>Card Expiry</B>

                                                    <h3>{{expiration_date}}</h3>
                                                </div>
                                                <div class="col-md-2">
                                                    <B>CVC</B>
                                                    <input placeholder="" type="text" name="name" class="m-t-5">
                                                </div>

                                          </div>
                                    </div>'

    #view to show for payment if not credit card info is stored for the user
    class PaymentFormView extends Marionette.ItemView

        template : '<div class="col-md-6">
                        <div class="card-wrapper"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-container active">
                            <form id="payment-form" autocomplete="off">
                                Enter your card information below.
                                You will receive a notification confirming your payment
                                shortly in your registered email. Once the payment is
                                processed you will get an invoice in
                                your registered email address.<br><br>

                                <div class="row form-row">
                                    <div class="col-md-5">
                                        <input placeholder="Card number" type="text"
                                        class="form-control" data-encrypted-name="credit_card_number"
                                        id="credit_card_number">
                                    </div>

                                    <div class="col-md-7">
                                        <input placeholder="Full name" type="text"
                                        data-encrypted-name="cardholder_name"
                                        class="form-control"
                                        id="cardholder_name">
                                    </div>

                                     <div class="col-md-3">
                                     <input placeholder="MM/YY" type="text"
                                     class="form-control" data-encrypted-name="expiration_date"
                                     id="expiration_date">
                                     </div>

                                     <div class="col-md-3">
                                        <input placeholder="CVC" type="text"
                                        class="form-control" data-encrypted-name="credit_card_cvv"
                                        id="credit_card_cvv">
                                     </div>
                                     <div class="col-md-5">
                                        <button type="button" class="btn btn-primary btn-cons" id="submit">
                                        <i class="icon-ok"></i>
                                            Submit
                                        </button>
                                     </div>
                                </div>
                            </form>
                        </div>
                        <div id="success-msg"></div>
                    </div>'

        onShow : ->
            @$el.find( '.active form' ).card
                container : @$el.find( '.card-wrapper' )

        #show sucess msg
        onPaymentSucess:(response)->
            @$el.find( '#success-msg' ).empty()
            msgText = response.msg
            msg ="<div class='alert alert-success'>
            <button class='close' data-dismiss='alert'>&times;</button>
                   #{msgText}<div>"
            @$el.find( '#success-msg' ).append(msg)


        events :->
            'click #submit' :->
                #get all card details for encryption
                creditCardNumber = @$el.find('#credit_card_number' ).val()
                cardholderName= @$el.find('#cardholder_name' ).val()
                expirationDate= @$el.find('#expiration_date' ).val()
                creditCardCvv= @$el.find('#credit_card_cvv' ).val()

                #The client side encryption key from the sandbox account of braintree
                clientSideEncryptionKey = "MIIBCgKCAQEA0fQXY7zHRl2PSEoZGOWDseI9MTDz2eO45C5M27KhN/HJXqi7sj8UDybrZJdsK+QL4Cw55r285Eeka+a5tAciEqd3E6YXkNokVmgo6/Wg21vYJKRvcnLkPE+J5iBFfQBBEMNKZMALl1P7HHkfOJsFZNO9+YOfiE+wl0QC8SnjZApftJ69ibbuFdFSR3L4kP6tZSQWeJS9WnkDzxGvRUyGFfs26x/q7Kxn+hdXkxTDd1o8FhjTCP/EkmHxhhJyYgzagtbJ84nxaLBuz6yW8bx5Qwt1ZiWUVVUIJlMiQtXUP05CId+aMIV8wX3OWtyAmTpn8N++tXYGjt/kY/bf8oY3yQIDAQAB"
                braintree = Braintree.create( clientSideEncryptionKey )

                #encrypt the card data
                creditCardNumber = braintree.encrypt(creditCardNumber)
                cardholderName = braintree.encrypt(cardholderName)
                expirationDate = braintree.encrypt(expirationDate)
                creditCardCvv = braintree.encrypt(creditCardCvv)

                #card data array
                data =
                    'creditCardNumber' : creditCardNumber
                    'cardholderName' : cardholderName
                    'expirationDate' : expirationDate
                    'creditCardCvv' : creditCardCvv
                    'braintree_customer_id' : @model.get 'braintree_customer_id'

                #send the card details to the controller for ajax event
                @trigger "user:credit:card:details", data


    # return the view instances as objects
    ChangePlanLayout : ChangePlanLayout
    ActiveSubscriptionView : ActiveSubscriptionView
    SelectedPlanView : SelectedPlanView
    PaymentCardView : PaymentCardView
    PaymentFormView : PaymentFormView









