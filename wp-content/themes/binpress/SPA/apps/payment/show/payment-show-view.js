var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'text!apps/payment/templates/paymentForm.html', 'braintree'], function(Marionette, paymentFormTpl, BrainTree) {
  var PaymentFormView, PaymentLayout;
  PaymentLayout = (function(_super) {
    __extends(PaymentLayout, _super);

    function PaymentLayout() {
      return PaymentLayout.__super__.constructor.apply(this, arguments);
    }

    PaymentLayout.prototype.template = '<div class="page-header"> <h1 class="normaltext-center"> <span class="p-r-10">Enter Your Payament Details</span> </h1> </div> <div class="row"> <div class="col-md-12"> <div class="tiles blue"> <div class="row"> <div class="col-md-3"> <div class="tiles-body"> <div > ACTIVE PLAN </div> <div class="heading"> <span class="animate-number" >Free</span> <a href="#" class="white-txt"><small class="tiles-title"> (Deactivite Plan)</small></a> </div> </div> </div> <div class="col-md-3"> <div class="tiles-body"> <div > ACTIVE SINCE </div> <div class="heading"> <span class="animate-number" >09/12/2014</span> </div> </div> </div> </div> </div> </div> </div> <div class="row"> <div class="col-md-9"> <div class="modal-body"> <div class="row"> <div id="display-payment-form"></div> </div> </div> </div> <div class="col-md-3"> <div class="alert alert-info"> <p class="m-b-20">Any change of plans in the midddle of cycle will be applicable from new cycle</p> <div class="grid simple"> <h2 class="bold text-center">Free<br><small class="text-danger" > US$0.00/month</small></h4> <hr> <ul class="list-unstyled text-center"> <li>Multiple Email Accounts </li> <li>99.9% Uptime </li> <li>Enterprise Level Storage </li> <li>Fully Managed VPS</li> <li>Reliable 24/7/365 Support</li> <li>Enterprise Level Storage </li> <li>Fully Managed VPS</li> <li>Reliable 24/7/365 Support</li> </ul> </div> </div> </div></div>';

    PaymentLayout.prototype.regions = {
      displayPaymentRegion: '#display-payment-form'
    };

    return PaymentLayout;

  })(Marionette.Layout);
  PaymentFormView = (function(_super) {
    __extends(PaymentFormView, _super);

    function PaymentFormView() {
      return PaymentFormView.__super__.constructor.apply(this, arguments);
    }

    PaymentFormView.prototype.template = paymentFormTpl;

    PaymentFormView.prototype.tagName = 'form';

    PaymentFormView.prototype.id = 'payment-form';

    PaymentFormView.prototype.onShow = function() {
      var ajaxSubmit, braintree, clientSideEncryptionKey;
      ajaxSubmit = (function(_this) {
        return function(e) {
          var ajaxAction;
          e.preventDefault();
          ajaxAction = "" + AJAXURL + "?action=make-user-payment";
          return $.post(ajaxAction, _this.$el.serialize(), function(response) {
            return console.log(response);
          });
        };
      })(this);
      clientSideEncryptionKey = window.CSEK;
      braintree = Braintree.create(clientSideEncryptionKey);
      return braintree.onSubmitEncryptForm('payment-form', ajaxSubmit);
    };

    return PaymentFormView;

  })(Marionette.ItemView);
  return {
    PaymentLayout: PaymentLayout,
    PaymentFormView: PaymentFormView
  };
});
