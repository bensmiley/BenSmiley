var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'msgbus', 'apps/payment/show/payment-show-controller'], function(App, msgbus) {
  return App.module('PaymentApp', function(PaymentApp, App, Backbone, Marionette, $, _) {
    var API, PaymentAppRouter;
    PaymentAppRouter = (function(_super) {
      __extends(PaymentAppRouter, _super);

      function PaymentAppRouter() {
        return PaymentAppRouter.__super__.constructor.apply(this, arguments);
      }

      PaymentAppRouter.prototype.appRoutes = {
        'payment': 'show'
      };

      return PaymentAppRouter;

    })(Marionette.AppRouter);
    API = {
      show: function() {
        return App.execute("show:payment:page", {
          region: App.mainContentRegion
        });
      }
    };
    return PaymentApp.on({
      'start': function() {
        return new PaymentAppRouter({
          controller: API
        });
      }
    });
  });
});
