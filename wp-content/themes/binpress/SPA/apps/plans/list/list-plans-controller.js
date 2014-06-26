// Generated by CoffeeScript 1.7.1
var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'msgbus', 'regioncontroller', 'apps/plans/list/list-plan-view'], function(App, msgbus, RegionController, PlanListView) {
  return App.module("PlansApp.List", function(List, App, BackBone, Marionette, $, _) {
    var PlansListController;
    PlansListController = (function(_super) {
      __extends(PlansListController, _super);

      function PlansListController() {
        this.showPlanListView = __bind(this.showPlanListView, this);
        return PlansListController.__super__.constructor.apply(this, arguments);
      }

      PlansListController.prototype.initialize = function(opts) {
        this.domainId = opts.domainID;
        this.subscriptionModel = msgbus.reqres.request("get:subscription:for:domain", this.domainId);
        this.subscriptionModel.fetch();
        msgbus.commands.execute("when:fetched", this.subscriptionModel, (function(_this) {
          return function() {
            return _this.getPlanCollection();
          };
        })(this));
        this.planCollection = msgbus.reqres.request("get:all:plans");
        this.planCollection.fetch();
        return this.show(new Marionette.LoadingView);
      };

      PlansListController.prototype.getPlanCollection = function() {
        return msgbus.commands.execute("when:fetched", this.planCollection, (function(_this) {
          return function() {
            return _this.showPlanListView();
          };
        })(this));
      };

      PlansListController.prototype.showPlanListView = function() {
        var planListShowView;
        planListShowView = this.getPlanListView(this.subscriptionModel);
        return this.show(planListShowView, {
          loading: true
        });
      };

      PlansListController.prototype.getPlanListView = function(subscriptionModel) {
        return new PlanListView({
          collection: this.planCollection,
          model: subscriptionModel,
          domainId: this.domainId
        });
      };

      return PlansListController;

    })(RegionController);
    return App.commands.setHandler("show:plans:list", function(options) {
      return new PlansListController(options);
    });
  });
});
