// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'text!apps/plans/templates/changePlanLayout.html', 'braintree', 'card'], function(Marionette, changePlanTpl, BrainTree, card) {
  var ActiveSubscriptionView, ChangePlanLayout, PaymentCardView, PaymentFormView, SelectedPlanView;
  ChangePlanLayout = (function(_super) {
    __extends(ChangePlanLayout, _super);

    function ChangePlanLayout() {
      return ChangePlanLayout.__super__.constructor.apply(this, arguments);
    }

    ChangePlanLayout.prototype.template = changePlanTpl;

    ChangePlanLayout.prototype.regions = {
      activeSubscriptionRegion: '#active-subscription',
      selectedPlanRegion: '#selected-plan',
      paymentViewRegion: '#payment-form'
    };

    return ChangePlanLayout;

  })(Marionette.Layout);
  ActiveSubscriptionView = (function(_super) {
    __extends(ActiveSubscriptionView, _super);

    function ActiveSubscriptionView() {
      return ActiveSubscriptionView.__super__.constructor.apply(this, arguments);
    }

    ActiveSubscriptionView.prototype.template = ' <div class="col-md-3"> <div class="tiles-body"> <div > ACTIVE PLAN </div> <div class="heading"> <span class="animate-number" >{{plan_name}}</span> </div> </div> </div> <div class="col-md-3"> <div class="tiles-body"> <div > ACTIVE SINCE </div> <div class="heading"> <span class="animate-number" >{{start_date}}</span> </div> </div> </div>';

    ActiveSubscriptionView.prototype.className = 'row';

    return ActiveSubscriptionView;

  })(Marionette.ItemView);
  SelectedPlanView = (function(_super) {
    __extends(SelectedPlanView, _super);

    function SelectedPlanView() {
      return SelectedPlanView.__super__.constructor.apply(this, arguments);
    }

    SelectedPlanView.prototype.template = '<h3>Selected plan</h3> <p class="m-b-20">Any change of plans in the midddle of cycle will be applicable from new cycle</p> <div class="grid simple"> <h2 class="bold text-center">{{plan_name}}<br> <small class="text-danger"> Rs.{{price}}/month</small> </h2> <hr> <ul class="list-unstyled text-center"> <li>Multiple Email Accounts</li> <li>99.9% Uptime</li> <li>Enterprise Level Storage</li> <li>Fully Managed VPS</li> <li>Reliable 24/7/365 Support</li> <li>Enterprise Level Storage</li> <li>Fully Managed VPS</li> <li>Reliable 24/7/365 Support</li> </ul> </div>';

    SelectedPlanView.prototype.className = 'alert alert-info';

    return SelectedPlanView;

  })(Marionette.ItemView);
  PaymentCardView = (function(_super) {
    __extends(PaymentCardView, _super);

    function PaymentCardView() {
      return PaymentCardView.__super__.constructor.apply(this, arguments);
    }

    PaymentCardView.prototype.template = '<div class="well well-large" style="background-color: #E4E4E4;"> <h3><span class="semi-bold">Card Details</span></h3> <div class="row"> <div class="col-md-3"> <B>Card Name</B> <h3>{{customer_name}}</h3> </div> <div class="col-md-4"> <B>Card Number</B> <h3>{{card_number}}</h3> </div> <div class="col-md-2"> <B>Card Expiry</B> <h3>{{expiration_date}}</h3> </div> <div class="col-md-2"> <B>CVC</B> <input placeholder="" type="text" name="name" class="m-t-5"> </div> </div> </div>';

    return PaymentCardView;

  })(Marionette.ItemView);
  PaymentFormView = (function(_super) {
    __extends(PaymentFormView, _super);

    function PaymentFormView() {
      return PaymentFormView.__super__.constructor.apply(this, arguments);
    }

    PaymentFormView.prototype.template = '<div class="col-md-5"> <div class="card-wrapper"></div> </div> <div class="col-md-6"> <div class="form-container active"> <form action=""> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam<br><br> <div class="row form-row"> <div class="col-md-5"> <input placeholder="Card number" type="text" name="number" class="form-control"> </div> <div class="col-md-7"> <input placeholder="Full name" type="text" name="name" class="form-control"> </div> </div> <div class="row form-row"> <div class="col-md-3"> <input placeholder="MM/YY" type="text" name="expiry" class="form-control"> </div> <div class="col-md-3"> <input placeholder="CVC" type="text" name="cvc" class="form-control"> </div> <div class="col-md-5"> <button type="submit" class="btn btn-primary btn-cons"><i class="icon-ok"></i> Submit </button> </div> </div> </form> </div> </div>';

    PaymentFormView.prototype.className = 'row';

    PaymentFormView.prototype.onShow = function() {
      return this.$el.find('.active form').card({
        container: this.$el.find('.card-wrapper')
      });
    };

    return PaymentFormView;

  })(Marionette.ItemView);
  return {
    ChangePlanLayout: ChangePlanLayout,
    ActiveSubscriptionView: ActiveSubscriptionView,
    SelectedPlanView: SelectedPlanView,
    PaymentCardView: PaymentCardView,
    PaymentFormView: PaymentFormView
  };
});
