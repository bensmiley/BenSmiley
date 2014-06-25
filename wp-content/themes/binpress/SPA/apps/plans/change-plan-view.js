(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['marionette', 'text!apps/plans/templates/changePlanLayout.html', 'text!apps/plans/templates/paymentForm.html', 'text!apps/plans/templates/paymentCard.html', 'braintree', 'card'], function(Marionette, changePlanTpl, paymentFormTpl, paymentCardTpl, BrainTree, card) {
    var ChangePlanLayout, DomainSubscriptionView, PaymentCardView, PaymentFormView, SelectedPlanView;
    ChangePlanLayout = (function(_super) {
      __extends(ChangePlanLayout, _super);

      function ChangePlanLayout() {
        return ChangePlanLayout.__super__.constructor.apply(this, arguments);
      }

      ChangePlanLayout.prototype.template = changePlanTpl;

      ChangePlanLayout.prototype.regions = {
        domainSubscriptionRegion: '#active-subscription',
        selectedPlanRegion: '#selected-plan',
        paymentViewRegion: '#payment-form'
      };

      return ChangePlanLayout;

    })(Marionette.Layout);
    DomainSubscriptionView = (function(_super) {
      __extends(DomainSubscriptionView, _super);

      function DomainSubscriptionView() {
        return DomainSubscriptionView.__super__.constructor.apply(this, arguments);
      }

      DomainSubscriptionView.prototype.template = ' <div class="col-md-3"> <div class="tiles-body"> <div >Domain name </div> <div class="heading"> <span class="animate-number" >{{post_title}}</span> </div> </div> </div> <div class="col-md-3"> <div class="tiles-body"> <div > Active plan </div> <div class="heading"> <span class="animate-number" >{{plan_name}}</span> </div> </div> </div> <div class="col-md-6"><div class="tiles-body">To ensure the plan is always active for your domain, enter valid card details below.Its easy to change your card information. Simply click on the change card button below and the current card details will be replaced by new card for the next billing cycle</div></div>';

      DomainSubscriptionView.prototype.className = 'row';

      return DomainSubscriptionView;

    })(Marionette.ItemView);
    SelectedPlanView = (function(_super) {
      __extends(SelectedPlanView, _super);

      function SelectedPlanView() {
        return SelectedPlanView.__super__.constructor.apply(this, arguments);
      }

      SelectedPlanView.prototype.template = '<ul class="ca-menu grow"><li class="plans"><h3 class="semi-bold text-center">Selected plan</h3> <div class="grid simple"> <h4 class="text-center semi-bold">{{plan_name}}<br> <small class="text-danger"> Rs.{{price}}/month</small> </h4> <hr> <ul class="list-unstyled"> <li>Multiple Email Accounts</li> <li>99.9% Uptime</li> <li>Enterprise Level Storage</li> <li>Fully Managed VPS</li> <li>Reliable 24/7/365 Support</li> <li>Enterprise Level Storage</li> <li>Fully Managed VPS</li> <li>Reliable 24/7/365 Support</li> </ul> <br> <p class="text-danger">Note:</p> <p class="text-muted">Any change of plans in the midddle of cycle will be applicable from new cycle</p> </div></li></ul>';

      return SelectedPlanView;

    })(Marionette.ItemView);
    PaymentCardView = (function(_super) {
      __extends(PaymentCardView, _super);

      function PaymentCardView() {
        return PaymentCardView.__super__.constructor.apply(this, arguments);
      }

      PaymentCardView.prototype.template = '<div class="well well-large well-up"> <div class="row"> <div class="col-md-12"> <h3><span class="semi-bold">Card Details</span></h3> <button type="button" class="btn btn-primary btn-cons pull-right" style="margin-top:-40px;" id="change-card"> <i class="icon-ok"></i> Change Card </button> </div></div> <div class="row"><br/> <div class="col-md-3"> <B>Card Holder Name</B> <h3>{{customer_name}}</h3> </div> <div class="col-md-4"> <B>Card Number</B> <h3>{{card_number}}</h3> </div> <div class="col-md-2"> <B>Card Expiry</B> <h3>{{expiration_date}}</h3> </div> <div class="col-md-2"> <B>CVC</B> <h3>***</h3> </div> </div> <div class="row"><br/> <div class="col-md-12"> <button type="button" class="btn btn-primary btn-cons top" id="submit"> <i class="icon-ok"></i> Pay </button> </div> </div> <div class="col-md-5 loader" style="display: none"> <img src="http://localhost/bensmiley/wp-content/themes/binpress/images/2.gif"> </div> <div class="col-md-5"> <div id="success-msg"></div> </div> </div>';

      PaymentCardView.prototype.events = function() {
        return {
          'click #submit': function() {
            var braintree, clientSideEncryptionKey, creditCardToken;
            creditCardToken = this.model.get('token');
            clientSideEncryptionKey = "MIIBCgKCAQEA0fQXY7zHRl2PSEoZGOWDseI9MTDz2eO45C5M27KhN/HJXqi7sj8UDybrZJdsK+QL4Cw55r285Eeka+a5tAciEqd3E6YXkNokVmgo6/Wg21vYJKRvcnLkPE+J5iBFfQBBEMNKZMALl1P7HHkfOJsFZNO9+YOfiE+wl0QC8SnjZApftJ69ibbuFdFSR3L4kP6tZSQWeJS9WnkDzxGvRUyGFfs26x/q7Kxn+hdXkxTDd1o8FhjTCP/EkmHxhhJyYgzagtbJ84nxaLBuz6yW8bx5Qwt1ZiWUVVUIJlMiQtXUP05CId+aMIV8wX3OWtyAmTpn8N++tXYGjt/kY/bf8oY3yQIDAQAB";
            braintree = Braintree.create(clientSideEncryptionKey);
            creditCardToken = braintree.encrypt(creditCardToken);
            this.trigger("user:card:payment", creditCardToken);
            return this.$el.find('.loader').show();
          },
          'click #change-card': function() {
            return this.trigger("change:card:clicked");
          }
        };
      };

      PaymentCardView.prototype.onPaymentSucess = function(response, domainId) {
        var msg, msgText;
        this.$el.find('#success-msg').empty();
        msgText = response.msg;
        msg = "<div class='alert alert-success'> <button class='close' data-dismiss='alert'>&times;</button> " + msgText + "<div>";
        return this.$el.find('#success-msg').append(msg);
      };

      PaymentCardView.prototype.redirectPage = function(redirect_url) {
        return window.location.href = redirect_url;
      };

      return PaymentCardView;

    })(Marionette.ItemView);
    PaymentFormView = (function(_super) {
      __extends(PaymentFormView, _super);

      function PaymentFormView() {
        return PaymentFormView.__super__.constructor.apply(this, arguments);
      }

      PaymentFormView.prototype.template = paymentFormTpl;

      PaymentFormView.prototype.onShow = function() {
        return this.$el.find('.active form').card({
          container: this.$el.find('.card-wrapper')
        });
      };

      PaymentFormView.prototype.onPaymentSucess = function(response, domainId) {
        var msg, msgText;
        this.$el.find('#success-msg').empty();
        msgText = response.msg;
        msg = "<div class='alert alert-success'> <button class='close' data-dismiss='alert'>&times;</button> " + msgText + "<div>";
        return this.$el.find('#success-msg').append(msg);
      };

      PaymentFormView.prototype.redirectPage = function(redirect_url) {
        return window.location.href = redirect_url;
      };

      PaymentFormView.prototype.events = function() {
        return {
          'click #submit': function() {
            var braintree, cardholderName, clientSideEncryptionKey, creditCardCvv, creditCardNumber, data, expirationDate;
            creditCardNumber = this.$el.find('#credit_card_number').val();
            cardholderName = this.$el.find('#cardholder_name').val();
            expirationDate = this.$el.find('#expiration_date').val();
            creditCardCvv = this.$el.find('#credit_card_cvv').val();
            clientSideEncryptionKey = "MIIBCgKCAQEA0fQXY7zHRl2PSEoZGOWDseI9MTDz2eO45C5M27KhN/HJXqi7sj8UDybrZJdsK+QL4Cw55r285Eeka+a5tAciEqd3E6YXkNokVmgo6/Wg21vYJKRvcnLkPE+J5iBFfQBBEMNKZMALl1P7HHkfOJsFZNO9+YOfiE+wl0QC8SnjZApftJ69ibbuFdFSR3L4kP6tZSQWeJS9WnkDzxGvRUyGFfs26x/q7Kxn+hdXkxTDd1o8FhjTCP/EkmHxhhJyYgzagtbJ84nxaLBuz6yW8bx5Qwt1ZiWUVVUIJlMiQtXUP05CId+aMIV8wX3OWtyAmTpn8N++tXYGjt/kY/bf8oY3yQIDAQAB";
            braintree = Braintree.create(clientSideEncryptionKey);
            creditCardNumber = braintree.encrypt(creditCardNumber);
            cardholderName = braintree.encrypt(cardholderName);
            expirationDate = braintree.encrypt(expirationDate);
            creditCardCvv = braintree.encrypt(creditCardCvv);
            data = {
              'creditCardNumber': creditCardNumber,
              'cardholderName': cardholderName,
              'expirationDate': expirationDate,
              'creditCardCvv': creditCardCvv,
              'braintree_customer_id': this.model.get('braintree_customer_id')
            };
            this.trigger("user:credit:card:details", data);
            return this.$el.find('.loader').show();
          },
          'click #cancel': function() {
            return this.trigger("use:stored:card");
          }
        };
      };

      return PaymentFormView;

    })(Marionette.ItemView);
    return {
      ChangePlanLayout: ChangePlanLayout,
      DomainSubscriptionView: DomainSubscriptionView,
      SelectedPlanView: SelectedPlanView,
      PaymentCardView: PaymentCardView,
      PaymentFormView: PaymentFormView
    };
  });

}).call(this);
