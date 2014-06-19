#include the files for the app
define [ 'marionette'
         'text!apps/plans/templates/changePlanLayout.html'
         'braintree'
         'card' ], ( Marionette, changePlanTpl, BrainTree, card )->


    # Payment page main layout
    class ChangePlanView extends Marionette.CompositeView

        template : changePlanTpl

        onShow : ->
            console.log @model

    #    class ActiveSubscriptionView extends Marionette.ItemView
    #        template : ' <div class="col-md-3">
    #                            <div class="tiles-body">
    #                                <div > ACTIVE PLAN </div>
    #                                <div class="heading">
    #                                    <span class="animate-number" >Free</span>
    #                                    <a href="#" class="white-txt"><small class="tiles-title"> (Deactivite Plan)</small></a>
    #                                </div>
    #                            </div>
    #                        </div>
    #                        <div class="col-md-3">
    #                            <div class="tiles-body">
    #                                <div > ACTIVE SINCE </div>
    #                                <div class="heading">
    #                                    <span class="animate-number" >09/12/2014</span>
    #                                </div>
    #                            </div>
    #                        </div>'
    #
    #        className : 'row'
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
    ChangePlanView









