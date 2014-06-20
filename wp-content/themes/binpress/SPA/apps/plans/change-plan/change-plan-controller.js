(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['app', 'regioncontroller', 'apps/plans/change-plan/change-plan-view', 'msgbus'], function(App, RegionController, ChangePlanView, msgbus) {
    return App.module("PlansApp.Change", function(Change, App, BackBone, Marionette, $, _) {
      var ChangePlanController;
      ChangePlanController = (function(_super) {
        __extends(ChangePlanController, _super);

        function ChangePlanController() {
          this.showPaymentFormView = __bind(this.showPaymentFormView, this);
          this.showPaymentCardView = __bind(this.showPaymentCardView, this);
          this.showPaymentView = __bind(this.showPaymentView, this);
          this.showSelectedPlanView = __bind(this.showSelectedPlanView, this);
          this.showActiveSubscriptionView = __bind(this.showActiveSubscriptionView, this);
          return ChangePlanController.__super__.constructor.apply(this, arguments);
        }

        ChangePlanController.prototype.initialize = function(opts) {
          this.domainId = opts.domainID;
          this.planId = opts.planID;
          this.layout = this.getLayout();
          this.show(this.layout, {
            loading: true
          });
          this.subscriptionModel = msgbus.reqres.request("get:subscription:for:domain", this.domainId);
          this.subscriptionModel.fetch();
          msgbus.commands.execute("when:fetched", this.subscriptionModel, (function(_this) {
            return function() {
              return _this.showActiveSubscriptionView();
            };
          })(this));
          this.selectedPlanModel = msgbus.reqres.request("get:plan:by:planid", this.planId);
          this.selectedPlanModel.fetch();
          msgbus.commands.execute("when:fetched", this.selectedPlanModel, (function(_this) {
            return function() {
              return _this.showSelectedPlanView();
            };
          })(this));
          this.userBillingModel = msgbus.reqres.request("get:user:billing:data");
          this.userBillingModel.fetch();
          return msgbus.commands.execute("when:fetched", this.userBillingModel, (function(_this) {
            return function() {
              return _this.showPaymentView();
            };
          })(this));
        };

        ChangePlanController.prototype.getActiveSubscriptionView = function(subscriptionModel) {
          return new ChangePlanView.ActiveSubscriptionView({
            model: subscriptionModel
          });
        };

        ChangePlanController.prototype.showActiveSubscriptionView = function() {
          var activeSubscriptionView;
          activeSubscriptionView = this.getActiveSubscriptionView(this.subscriptionModel);
          return this.layout.activeSubscriptionRegion.show(activeSubscriptionView);
        };

        ChangePlanController.prototype.getSelectedPlanViewView = function(selectedPlanModel) {
          return new ChangePlanView.SelectedPlanView({
            model: selectedPlanModel
          });
        };

        ChangePlanController.prototype.showSelectedPlanView = function() {
          var selectedPlanView;
          selectedPlanView = this.getSelectedPlanViewView(this.selectedPlanModel);
          return this.layout.selectedPlanRegion.show(selectedPlanView);
        };

        ChangePlanController.prototype.getLayout = function() {
          return new ChangePlanView.ChangePlanLayout;
        };

        ChangePlanController.prototype.showPaymentView = function() {
          var cardExists;
          cardExists = this.userBillingModel.get('card_exists');
          if (cardExists) {
            return this.showPaymentCardView();
          } else {
            return this.showPaymentFormView();
          }
        };

        ChangePlanController.prototype.showPaymentCardView = function() {
          var paymentCardView;
          paymentCardView = this.getPaymentCardView(this.userBillingModel);
          return this.layout.paymentViewRegion.show(paymentCardView);
        };

        ChangePlanController.prototype.getPaymentCardView = function(userBillingModel) {
          return new ChangePlanView.PaymentCardView({
            model: userBillingModel
          });
        };

        ChangePlanController.prototype.showPaymentFormView = function() {
          var paymentFormView;
          paymentFormView = this.getPaymentFormView(this.userBillingModel);
          return this.layout.paymentViewRegion.show(paymentFormView);
        };

        ChangePlanController.prototype.getPaymentFormView = function(userBillingModel) {
          return new ChangePlanView.PaymentFormView({
            model: userBillingModel
          });
        };

        return ChangePlanController;

      })(RegionController);
      return App.commands.setHandler("change:plan", function(opts) {
        return new ChangePlanController(opts);
      });
    });
  });

}).call(this);
