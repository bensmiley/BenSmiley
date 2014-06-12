#include the files for the app
define [ 'app'
         'text!apps/user-profile/templates/userprofile.html'
         'braintree'], ( App, userProfileTpl, BrainTree )->

    # Payment page main layout
    class PaymentLayout extends Marionette.Layout

        template : '<div class="page-header">
                      <h1 class="normaltext-center">
                        <span class="p-r-10">Enter Your Payament Details</span>
                      </h1>
                    </div>
                     <div class="row">
                        <div class="col-md-12">
                                 <div class="tiles blue">
                                      <div class="row">
                                          <div class="col-md-3">
                                               <div class="tiles-body">
                                                     <div > ACTIVE PLAN </div>
                                                          <div class="heading">
                                                          <span class="animate-number" >Free</span>
                                                          <a href="#" class="white-txt"><small class="tiles-title"> (Deactivite Plan)</small></a>
                                                     </div>
                                               </div>
                                           </div>
                                          <div class="col-md-3">
                                                 <div class="tiles-body">
                                                     <div > ACTIVE SINCE </div>
                                                          <div class="heading">
                                                          <span class="animate-number" >09/12/2014</span>
                                                      </div>
                                               </div>
                                          </div>
                                      </div>
                                 </div>
                           </div>
                      </div>
                      <div class="col-md-9">
                	<div class="modal-body">
                	<div class="row">
                    	  <div id="display-payment"></div>

                    </div>
                    </div>
                </div>
                <div class="col-md-3">
                	<div class="alert alert-info">
                	<p class="m-b-20">Any change of plans in the midddle of cycle will be applicable from new cycle</p>
                		<div class="grid simple">
                			<h2 class="bold text-center">Free<br><small class="text-danger" > US$0.00/month</small></h4>
                			<hr>

                			<ul class="list-unstyled text-center">
                				<li>Multiple Email Accounts </li>
                				<li>99.9% Uptime </li>
                				<li>Enterprise Level Storage </li>
                				<li>Fully Managed VPS</li>
                				<li>Reliable 24/7/365 Support</li>
                				<li>Enterprise Level Storage </li>
                				<li>Fully Managed VPS</li>
                				<li>Reliable 24/7/365 Support</li>
                			</ul>
                		</div>
                	</div>
                </div>'

        regions :
            displayPaymentRegion : '#display-payment'

    #payment form display
    class PaymentFormView extends Marionette.ItemView

        template : '
                    	<div class="row form-row">
                			<div class="col-md-8">
                			<input type="text" class="form-control" placeholder="Your Card Number"
                             name="transaction[credit_card][number]" id="braintree_credit_card_number"
                             value="4111111111111111" data-encrypted-name="number">
                			</div>
                			<div class="col-md-4">
                			<input type="text" class="form-control" placeholder="CVV"
                            name="transaction[credit_card][cvv]" data-encrypted-name="cvv" >
                			</div>
                		</div>

                		<div class="row form-row">
                			<div class="col-md-12">
                			<input type="text" class="form-control" placeholder="Card Holders Name"
                            name="transaction[customer][first_name]" ></div>
                		</div>

                		<div class="row form-row">
                			<div class="col-md-6">
                				<div class="input-append success date col-md-12 col-lg-12 no-padding">

                                 <input type="text" name="transaction[credit_card][expiration_date]"
                                 id="braintree_credit_card_exp" value="12/2015" data-encrypted-name="expiration_date">
               					<!--<input type="text" class="form-control">
                					<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i>
                					</span> -->
                				</div>

                				<button type="submit" class="btn btn-primary" id="btn-make-pay">Upgrade Plan</button>
                				<button type="button" class="btn btn-danger">Cancel and choose another plan</button>
                			</div>
                		</div>
                '

        tagName : 'form'

        id: 'payment-form'

#        events:
#            'click #btn-make-pay':->
#                if @$el.valid()
#                    userdata = Backbone.Syphon.serialize @
#                    @trigger "make:payment:click", userdata
        onShow :->
            ajax_submit  = (e) =>
                e.preventDefault()
                ajaxAction = AJAXURL+'?action=make-user-payment'
                $.post ajaxAction,@$el.serialize(),(response)->
                    console.log response


            braintree = Braintree.create("MIIBCgKCAQEA0fQXY7zHRl2PSEoZGOWDseI9MTDz2eO45C5M27KhN/HJXqi7sj8UDybrZJdsK+QL4Cw55r285Eeka+a5tAciEqd3E6YXkNokVmgo6/Wg21vYJKRvcnLkPE+J5iBFfQBBEMNKZMALl1P7HHkfOJsFZNO9+YOfiE+wl0QC8SnjZApftJ69ibbuFdFSR3L4kP6tZSQWeJS9WnkDzxGvRUyGFfs26x/q7Kxn+hdXkxTDd1o8FhjTCP/EkmHxhhJyYgzagtbJ84nxaLBuz6yW8bx5Qwt1ZiWUVVUIJlMiQtXUP05CId+aMIV8wX3OWtyAmTpn8N++tXYGjt/kY/bf8oY3yQIDAQAB");
            braintree.onSubmitEncryptForm('payment-form', ajax_submit);



    # return the view instances as objects
    PaymentLayout : PaymentLayout
    PaymentFormView : PaymentFormView









