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
          this.changePlan = __bind(this.changePlan, this);
          return ChangePlanController.__super__.constructor.apply(this, arguments);
        }

        ChangePlanController.prototype.initialize = function(opts) {
          var subscriptionModel;
          this.domainId = opts.domainID;
          subscriptionModel = msgbus.reqres.request("get:subscription:for:domain", this.domainId);
          return subscriptionModel.fetch({
            success: this.changePlan
          });
        };

        ChangePlanController.prototype.changePlan = function(subscriptionModel) {
          var changePlanView;
          changePlanView = this.getChangePlanView(subscriptionModel);
          return this.show(changePlanView, {
            loading: true
          });
        };

        ChangePlanController.prototype.getChangePlanView = function(subscriptionModel) {
          return new ChangePlanView({
            model: subscriptionModel
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
