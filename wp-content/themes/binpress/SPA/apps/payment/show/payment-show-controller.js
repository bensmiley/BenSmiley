var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'msgbus', 'regioncontroller', 'apps/payment/show/payment-show-view'], function(App, msgbus, RegionController, PaymentView) {
  return App.module("PaymentApp.Show", function(Show, App, BackBone, Marionette, $, _) {
    var PaymentController;
    PaymentController = (function(_super) {
      __extends(PaymentController, _super);

      function PaymentController() {
        return PaymentController.__super__.constructor.apply(this, arguments);
      }

      PaymentController.prototype.initialize = function(opts) {
        this.layout = this.getLayout();
        this.listenTo(this.layout, "show", this.showPaymentForm);
        return this.show(this.layout);
      };

      PaymentController.prototype.getLayout = function() {
        return new PaymentView.PaymentLayout;
      };

      PaymentController.prototype.showPaymentForm = function() {
        var paymentFormView;
        paymentFormView = new PaymentView.PaymentFormView;
        return this.layout.displayPaymentRegion.show(paymentFormView);
      };

      return PaymentController;

    })(RegionController);
    return App.commands.setHandler("show:payment:page", function(options) {
      return new PaymentController(options);
    });
  });
});
