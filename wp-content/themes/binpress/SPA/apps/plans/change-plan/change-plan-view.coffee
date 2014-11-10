#include the files for the app
define [ 'marionette'
         'text!apps/plans/templates/changePlanLayout.html'
         'text!apps/plans/templates/paymentForm.html'
         'text!apps/plans/templates/paymentCard.html'
         'braintree'
         'card' ], ( Marionette, changePlanTpl, paymentFormTpl,paymentCardTpl, BrainTree, card )->


    # Payment page main layout
    class ChangePlanLayout extends Marionette.Layout

        template : changePlanTpl

        regions :
            domainSubscriptionRegion : '#active-subscription'
            selectedPlanRegion : '#selected-plan'
            paymentViewRegion : '#payment-form'

    #view to show the active subscription
    class DomainSubscriptionView extends Marionette.ItemView
        template : ' <div class="col-md-3">
                                <div class="tiles-body">
                                    <div >Domain name </div>
                                    <div class="heading">
                                        <span class="animate-number" >{{post_title}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="tiles-body">
                                    <div > Active plan </div>
                                    <div class="heading">
                                        <span class="animate-number" >{{plan_name}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"><div class="tiles-body">To ensure the plan is always active for your domain,
                            enter valid card details below.Its easy to change your card information.
                            Simply click on the change card button below and the current card details
                             will be replaced by new card for the next billing cycle</div></div>'

        className : 'row'

    #view to show the selected plan
    class SelectedPlanView extends Marionette.ItemView
        template : '<ul class="ca-menu grow"><li class="plans"><h3 class="semi-bold text-center">Selected plan</h3>

                    <div class="grid simple">
                        <h4 class="text-center semi-bold">{{plan_name}}<br>
                            <small class="text-danger"> ${{price}}/month</small>
                        </h4>
                        <hr>
                        {{{description}}}
                        <br>
                        <p class="text-danger">Note:</p>
                        <p class="text-muted">Any change of plans in the midddle of cycle
                     will be applicable from new cycle</p>
                    </div></li></ul>'


    #view to show the credit card info stored for the user for billing
    class PaymentCardView extends Marionette.ItemView

        template : paymentCardTpl
        events : ->
            'click #submit' : ->
                console.log "PAy using stored card"
                #get all card details for encryption
                creditCardToken = @model.get 'token'

                #The client side encryption key from the sandbox account of braintree
                clientSideEncryptionKey = window.CSEK
                braintree = Braintree.create( clientSideEncryptionKey )

                #encrypt the card data
                creditCardToken = braintree.encrypt( creditCardToken )

                #send the card details to the controller for ajax event
                @trigger "user:card:payment", creditCardToken

                #show the loader
                @$el.find( '.ajax-loader-login' ).show()

            'click #change-card' : ->
                @trigger "change:card:clicked"



        #show sucess msg
        onPaymentSucess : ( response, domainId )->
            #hide the loader
            $( '.ajax-loader-login' ).hide()

            @$el.find( '#success-msg' ).empty()
            msgText = response.msg
            msg = "<div class='alert alert-success'>
                    <button class='close' data-dismiss='alert'>&times;</button>
                           #{msgText}<div>"
            @$el.find( '#success-msg' ).append( msg )

            #redirect the page to domain page if payment success
#            mainUrl = window.location.href.replace Backbone.history.getFragment(), ''
#            redirect_url = "#{mainUrl}domains/edit/#{domainId}/list-plan"
#            _.delay =>
#                @redirectPage redirect_url
#            , 2000

        redirectPage : ( redirect_url )->
            window.location.href = redirect_url


    #view to show for payment if not credit card info is stored for the user
    class PaymentFormView extends Marionette.ItemView

        template : paymentFormTpl

        onShow : ->
            @$el.find( '.active form' ).card
                container : @$el.find( '.card-wrapper' )

            #check if change card clicked view
            cardExists = @model.get 'card_exists'
            if cardExists
                @$el.find( '.cancel-card' ).show()

        #show sucess msg
        onPaymentSuccess : ( msgText )->
            #hide the loader
            $( '.ajax-loader-login' ).hide()

            @$el.find( '#success-msg' ).empty()
            
            msg = "<div class='alert alert-success'>
                    <button class='close' data-dismiss='alert'>&times;</button>"+msgText+"<div>"
            @$el.find( '#success-msg' ).append( msg )

            #redirect the page to domain page
#            mainUrl = window.location.href.replace Backbone.history.getFragment(), ''
#            redirect_url = "#{mainUrl}domains/edit/#{domainId}/list-plan"
#            _.delay =>
#                @redirectPage redirect_url
#            , 2000
        
        onPaymentError : ( msgText )->
            #hide the loader
            $( '.ajax-loader-login' ).hide()

            @$el.find( '#success-msg' ).empty()
            
            msg = "<div class='alert alert-success'>
                    <button class='close' data-dismiss='alert'>&times;</button>"+msgText+"<div>"
            @$el.find( '#success-msg' ).append( msg )

        redirectPage : ( redirect_url )->
            window.location.href = redirect_url

        events : ->
            'click #submit' : (e)->
                e.preventDefault()
                # console.log @model
                # console.log "Submit credit card details"

                #show the loader
                @$el.find( '.ajax-loader-login' ).show()

                # Extract all credit card info from the form
                cardNumber = @$el.find( '#credit_card_number' ).val()
                nameOnCard = @$el.find( '#cardholder_name' ).val()
                expirationDate = @$el.find( '#expiration_date' ).val()
                expirationDate = expirationDate.replace RegExp(" ", "g"), ""
                # console.log expirationDate
                cvv = @$el.find( '#credit_card_cvv' ).val()

                clientToken = @model.get 'braintree_client_token'
                # console.log clientToken
                client = new braintree.api.Client(clientToken: clientToken)

                # console.log client 
                client.tokenizeCard number : cardNumber, cvv : cvv, cardholderName : nameOnCard, expiration_date : expirationDate, ( err, nonce )=>
                    @trigger "new:credit:card:payment", nonce
                #=======================MAHIMA's OLD CODE BEGINS=======================
                # #get all card details for encryption
                # creditCardNumber = @$el.find( '#credit_card_number' ).val()
                # cardholderName = @$el.find( '#cardholder_name' ).val()
                # expirationDate = @$el.find( '#expiration_date' ).val()
                # creditCardCvv = @$el.find( '#credit_card_cvv' ).val()

                # #The client side encryption key from the sandbox account of braintree
                # # clientSideEncryptionKey = "MIIBCgKCAQEA0fQXY7zHRl2PSEoZGOWDseI9MTDz2eO45C5M27KhN/HJXqi7sj8UDybrZJdsK+QL4Cw55r285Eeka+a5tAciEqd3E6YXkNokVmgo6/Wg21vYJKRvcnLkPE+J5iBFfQBBEMNKZMALl1P7HHkfOJsFZNO9+YOfiE+wl0QC8SnjZApftJ69ibbuFdFSR3L4kP6tZSQWeJS9WnkDzxGvRUyGFfs26x/q7Kxn+hdXkxTDd1o8FhjTCP/EkmHxhhJyYgzagtbJ84nxaLBuz6yW8bx5Qwt1ZiWUVVUIJlMiQtXUP05CId+aMIV8wX3OWtyAmTpn8N++tXYGjt/kY/bf8oY3yQIDAQAB"
                # clientSideEncryptionKey = window.CSEK
                # braintree = Braintree.create( clientSideEncryptionKey )

                # #encrypt the card data
                # creditCardNumber = braintree.encrypt( creditCardNumber )
                # cardholderName = braintree.encrypt( cardholderName )
                # expirationDate = braintree.encrypt( expirationDate )
                # creditCardCvv = braintree.encrypt( creditCardCvv )

                # #card data array
                # data =
                #     'creditCardNumber' : creditCardNumber
                #     'cardholderName' : cardholderName
                #     'expirationDate' : expirationDate
                #     'creditCardCvv' : creditCardCvv
                #     'braintree_customer_id' : @model.get 'braintree_customer_id'

                # #send the card details to the controller for ajax event
                # @trigger "user:credit:card:details", data
                #=======================MAHIMA's OLD CODE ENDS=======================

            'click #cancel' : ->
                @trigger "use:stored:card"


    # return the view instances as objects
    ChangePlanLayout : ChangePlanLayout
    DomainSubscriptionView : DomainSubscriptionView
    SelectedPlanView : SelectedPlanView
    PaymentCardView : PaymentCardView
    PaymentFormView : PaymentFormView









