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

    SelectedPlanView.prototype.template = '<ul class="ca-menu grow"><li class="plans"><h3 class="semi-bold text-center">Selected plan</h3> <div class="grid simple"> <h4 class="text-center semi-bold">{{plan_name}}<br> <small class="text-danger"> ${{price}}/month</small> </h4> <hr> {{{description}}} <br> <p class="text-danger">Note:</p> <p class="text-muted">Any change of plans in the midddle of cycle will be applicable from new cycle</p> </div></li></ul>';

    return SelectedPlanView;

  })(Marionette.ItemView);
  PaymentCardView = (function(_super) {
    __extends(PaymentCardView, _super);

    function PaymentCardView() {
      return PaymentCardView.__super__.constructor.apply(this, arguments);
    }

    PaymentCardView.prototype.template = paymentCardTpl;

    PaymentCardView.prototype.events = function() {
      return {
        'click #submit': function() {
          var braintree, clientSideEncryptionKey, creditCardToken;
          console.log("PAy using stored card");
          creditCardToken = this.model.get('token');
          clientSideEncryptionKey = window.CSEK;
          braintree = Braintree.create(clientSideEncryptionKey);
          creditCardToken = braintree.encrypt(creditCardToken);
          this.trigger("user:card:payment", creditCardToken);
          return this.$el.find('.ajax-loader-login').show();
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
      var cardExists;
      this.$el.find('.active form').card({
        container: this.$el.find('.card-wrapper')
      });
      cardExists = this.model.get('card_exists');
      if (cardExists) {
        return this.$el.find('.cancel-card').show();
      }
    };

    PaymentFormView.prototype.onPaymentSucess = function(msgText) {
      var msg;
      this.$el.find('#success-msg').empty();
      this.$el.find('.ajax-loader-login').hide();
      msg = "<div class='alert alert-success'> <button class='close' data-dismiss='alert'>&times;</button> " + msgText + "<div>";
      return this.$el.find('#success-msg').append(msg);
    };

    PaymentFormView.prototype.onPaymentError = function(msgText) {
      var msg;
      this.$el.find('#success-msg').empty();
      this.$el.find('.ajax-loader-login').hide();
      msg = "<div class='alert alert-success'> <button class='close' data-dismiss='alert'>&times;</button> " + msgText + "<div>";
      return this.$el.find('#success-msg').append(msg);
    };

    PaymentFormView.prototype.redirectPage = function(redirect_url) {
      return window.location.href = redirect_url;
    };

    PaymentFormView.prototype.events = function() {
      return {
        'click #submit': function(e) {
          var cardNumber, client, clientToken, cvv, expirationDate, nameOnCard;
          e.preventDefault();
          this.$el.find('.ajax-loader-login').show();
          cardNumber = this.$el.find('#credit_card_number').val();
          nameOnCard = this.$el.find('#cardholder_name').val();
          expirationDate = this.$el.find('#expiration_date').val();
          expirationDate = expirationDate.replace(RegExp(" ", "g"), "");
          cvv = this.$el.find('#credit_card_cvv').val();
          clientToken = this.model.get('braintree_client_token');
          client = new braintree.api.Client({
            clientToken: clientToken
          });
          return client.tokenizeCard({
            number: cardNumber,
            cvv: cvv,
            cardholderName: nameOnCard,
            expiration_date: expirationDate
          }, (function(_this) {
            return function(err, nonce) {
              return _this.trigger("new:credit:card:payment", nonce);
            };
          })(this));
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
