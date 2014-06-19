#include the files for the app
define [ 'marionette'
         'text!apps/plans/templates/changePlanLayout.html'
         'braintree'
         'card' ], ( Marionette, changePlanTpl, BrainTree, card )->


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
        template : '<h3>Selected plan</h3>
                            <p class="m-b-20">Any change of plans in the midddle of cycle
                             will be applicable from new cycle</p>

                            <div class="grid simple">
                                <h2 class="bold text-center">{{plan_name}}<br>
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

        template : '<div class="col-md-5">
                                        <div class="card-wrapper"></div>
                                    </div>
                                    <div class="col-md-6">


                                        <div class="form-container active">
                                            <form action="">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam<br><br>

                                                <div class="row form-row">
                                                    <div class="col-md-5">
                                                        <input placeholder="Card number" type="text" name="number" class="form-control">
                                                    </div>
                                                    <div class="col-md-7">
                                                        <input placeholder="Full name" type="text" name="name" class="form-control">

                                                    </div>
                                                </div>

                                                <div class="row form-row">
                                                    <div class="col-md-3">
                                                        <input placeholder="MM/YY" type="text" name="expiry" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input placeholder="CVC" type="text" name="cvc" class="form-control">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <button type="submit" class="btn btn-primary btn-cons"><i class="icon-ok"></i>
                                                            Submit
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>

                                '
        className : 'row'

        onShow : ->
            @$el.find( '.active form' ).card
                container : @$el.find( '.card-wrapper' )


    #payment form display
    #    class PaymentFormView extends Marionette.ItemView
    #
    #        template : paymentFormTpl
    #
    #        tagName : 'form'
    #
    #        id : 'payment-form'
    #
    #        onShow : ->
    #            # The ajax submit action to be performed after braintree
    #            # encrypts the payment form.Braintree addes a onSubmit
    #            # handler after form encryption and attaches the ajax_submit
    #            # action to the OnSubmit handler.
    #            ajaxSubmit = ( e ) =>
    #                e.preventDefault()
    #                ajaxAction = "#{AJAXURL}?action=make-user-payment"
    #                $.post ajaxAction, @$el.serialize(), ( response )->
    #                    console.log response
    #
    #            #The client side encryption key from the sandbox account of braintree
    #            clientSideEncryptionKey = "MIIBCgKCAQEA0fQXY7zHRl2PSEoZGOWDseI9MTDz2eO45C5M27KhN/HJXqi7sj8UDybrZJdsK+QL4Cw55r285Eeka+a5tAciEqd3E6YXkNokVmgo6/Wg21vYJKRvcnLkPE+J5iBFfQBBEMNKZMALl1P7HHkfOJsFZNO9+YOfiE+wl0QC8SnjZApftJ69ibbuFdFSR3L4kP6tZSQWeJS9WnkDzxGvRUyGFfs26x/q7Kxn+hdXkxTDd1o8FhjTCP/EkmHxhhJyYgzagtbJ84nxaLBuz6yW8bx5Qwt1ZiWUVVUIJlMiQtXUP05CId+aMIV8wX3OWtyAmTpn8N++tXYGjt/kY/bf8oY3yQIDAQAB"
    #            braintree = Braintree.create( clientSideEncryptionKey )
    #
    #            #Encrypt the form using the formId and add the ajax action
    #            # after form encryption
    #            braintree.onSubmitEncryptForm( 'payment-form', ajaxSubmit )


    # return the view instances as objects
    ChangePlanLayout : ChangePlanLayout
    ActiveSubscriptionView : ActiveSubscriptionView
    SelectedPlanView : SelectedPlanView
    PaymentCardView : PaymentCardView
    PaymentFormView : PaymentFormView









