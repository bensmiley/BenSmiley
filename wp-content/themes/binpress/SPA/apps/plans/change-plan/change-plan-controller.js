var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'apps/plans/change-plan/change-plan-view', 'msgbus'], function(App, RegionController, ChangePlanView, msgbus) {
  return App.module("PlansApp.Change", function(Change, App, BackBone, Marionette, $, _) {
    var ChangePlanController;
    ChangePlanController = (function(_super) {
      __extends(ChangePlanController, _super);

      function ChangePlanController() {
        this.newCardPayment = __bind(this.newCardPayment, this);
        this.showPaymentFormView = __bind(this.showPaymentFormView, this);
        this.showPaymentCardView = __bind(this.showPaymentCardView, this);
        this.showPaymentView = __bind(this.showPaymentView, this);
        this.showSelectedPlanView = __bind(this.showSelectedPlanView, this);
        this.showDomainSubscriptionView = __bind(this.showDomainSubscriptionView, this);
        return ChangePlanController.__super__.constructor.apply(this, arguments);
      }

      ChangePlanController.prototype.initialize = function(opts) {
        this.domainId = opts.domainID;
        this.planId = opts.planID;
        this.layout = this.getLayout();
        this.listenTo(this.layout, "show", function() {
          this.layout.selectedPlanRegion.show(new Marionette.LoadingView);
          this.layout.domainSubscriptionRegion.show(new Marionette.LoadingView);
          return this.layout.paymentViewRegion.show(new Marionette.LoadingView);
        });
        this.show(this.layout, {
          loading: true
        });
        this.domainModel = msgbus.reqres.request("get:domain:model:by:id", this.domainId);
        msgbus.commands.execute("when:fetched", this.domainModel, (function(_this) {
          return function() {
            return _this.showDomainSubscriptionView();
          };
        })(this));
        this.selectedPlanModel = msgbus.reqres.request("get:plan:by:planid", this.planId);
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

      ChangePlanController.prototype.getDomainSubscriptionView = function(domainModel) {
        return new ChangePlanView.DomainSubscriptionView({
          model: domainModel
        });
      };

      ChangePlanController.prototype.showDomainSubscriptionView = function() {
        var domainSubscriptionView;
        domainSubscriptionView = this.getDomainSubscriptionView(this.domainModel);
        return this.layout.domainSubscriptionRegion.show(domainSubscriptionView);
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
        this.paymentCardView = this.getPaymentCardView(this.userBillingModel);
        this.layout.paymentViewRegion.show(this.paymentCardView);
        this.listenTo(this.paymentCardView, 'user:card:payment', this.creditCardPayment);
        return this.listenTo(this.paymentCardView, 'change:card:clicked', this.showPaymentFormView);
      };

      ChangePlanController.prototype.getPaymentCardView = function(userBillingModel) {
        return new ChangePlanView.PaymentCardView({
          model: userBillingModel
        });
      };

      ChangePlanController.prototype.showPaymentFormView = function() {
        this.paymentFormView = this.getPaymentFormView(this.userBillingModel);
        this.layout.paymentViewRegion.show(this.paymentFormView);
        this.listenTo(this.paymentFormView, 'use:stored:card', this.useStoredCreditCard);
        return this.listenTo(this.paymentFormView, "new:credit:card:payment", this.newCardPayment);
      };

      ChangePlanController.prototype.useStoredCreditCard = function() {
        return this.showPaymentCardView();
      };

      ChangePlanController.prototype.getPaymentFormView = function(userBillingModel) {
        return new ChangePlanView.PaymentFormView({
          model: userBillingModel
        });
      };

      ChangePlanController.prototype.newCardPayment = function(paymentMethodNonce) {
        var options;
        options = {
          method: 'POST',
          url: AJAXURL,
          data: {
            'paymentMethodNonce': paymentMethodNonce,
            'selectedPlanId': this.planId,
            'customerId': this.userBillingModel.get('braintree_customer_id'),
            'currentSubscriptionId': this.domainModel.get('subscription_id'),
            'selectedPlanName': this.selectedPlanModel.get('plan_name'),
            'selectedPlanPrice': this.selectedPlanModel.get('price'),
            'domainId': this.domainId,
            'activePlanId': this.domainModel.get('plan_id'),
            'action': 'user-new-payment'
          }
        };
        return $.ajax(options).done((function(_this) {
          return function(response) {
            if (response.code === "OK") {
              return _this.paymentFormView.triggerMethod("payment:success", response.msg);
            } else {
              return _this.paymentFormView.triggerMethod("payment:error", response.msg);
            }
          };
        })(this));
      };

      ChangePlanController.prototype.creditCardPayment = function(creditCardToken) {
        var options;
        options = {
          url: AJAXURL,
          method: "POST",
          data: {
            action: 'user-make-payment',
            creditCardToken: creditCardToken,
            selectedPlanId: this.planId,
            selectedPlanName: this.selectedPlanModel.get('plan_name'),
            selectedPlanPrice: this.selectedPlanModel.get('price'),
            domainId: this.domainId,
            activePlanId: this.domainModel.get('plan_id'),
            subscriptionId: this.domainModel.get('subscription_id')
          }
        };
        return $.ajax(options).done((function(_this) {
          return function(response) {
            return _this.paymentCardView.triggerMethod("payment:sucess", response, _this.domainId);
          };
        })(this));
      };

      return ChangePlanController;

    })(RegionController);
    return App.commands.setHandler("change:plan", function(opts) {
      return new ChangePlanController(opts);
    });
  });
});
