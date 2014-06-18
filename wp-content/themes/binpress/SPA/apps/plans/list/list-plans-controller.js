(function() {
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
          this.getPlanCollection = __bind(this.getPlanCollection, this);
          return PlansListController.__super__.constructor.apply(this, arguments);
        }

        PlansListController.prototype.initialize = function(opts) {
          var subscriptionModel;
          this.domainId = opts.domainID;
          subscriptionModel = msgbus.reqres.request("get:subscription:for:domain", this.domainId);
          return subscriptionModel.fetch({
            success: this.getPlanCollection
          });
        };

        PlansListController.prototype.getPlanCollection = function(subscriptionModel) {
          this.subscriptionModel = subscriptionModel;
          this.planCollection = msgbus.reqres.request("get:all:plans");
          return this.planCollection.fetch({
            success: this.showPlanListView
          });
        };

        PlansListController.prototype.showPlanListView = function(planCollection) {
          var planListShowView;
          planListShowView = this.getPlanListView(planCollection);
          return this.show(planListShowView, {
            loading: true
          });
        };

        PlansListController.prototype.getPlanListView = function(planCollection) {
          return new PlanListView({
            collection: planCollection,
            model: this.subscriptionModel
          });
        };

        return PlansListController;

      })(RegionController);
      return App.commands.setHandler("show:plans:list", function(options) {
        return new PlansListController(options);
      });
    });
  });

}).call(this);
