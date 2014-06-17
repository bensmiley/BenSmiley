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
          this.showPlansList = __bind(this.showPlansList, this);
          this.showActiveSubscriptionView = __bind(this.showActiveSubscriptionView, this);
          this.showActiveSubscription = __bind(this.showActiveSubscription, this);
          this.showViews = __bind(this.showViews, this);
          return PlansListController.__super__.constructor.apply(this, arguments);
        }

        PlansListController.prototype.initialize = function(opts) {
          this.domainId = opts.domainID;
          this.layout = this.getLayout();
          this.listenTo(this.layout, "show", this.showViews);
          return this.show(this.layout, {
            loading: true
          });
        };

        PlansListController.prototype.getLayout = function() {
          return new PlanListView.PlansListLayout;
        };

        PlansListController.prototype.showViews = function() {
          this.showActiveSubscription();
          return this.showPlansList();
        };

        PlansListController.prototype.showActiveSubscription = function() {
          this.subscriptionModel = msgbus.reqres.request("get:subscription:for:domain", this.domainId);
          return this.subscriptionModel.fetch({
            success: this.showActiveSubscriptionView
          });
        };

        PlansListController.prototype.showActiveSubscriptionView = function(subscriptionModel) {
          var activeSubscriptionView;
          activeSubscriptionView = this.getActiveSubscriptionView(subscriptionModel);
          return this.layout.activeSubscriptionRegion.show(activeSubscriptionView);
        };

        PlansListController.prototype.getActiveSubscriptionView = function(subscriptionModel) {
          return new PlanListView.ActiveSubscriptionView({
            model: subscriptionModel
          });
        };

        PlansListController.prototype.showPlansList = function() {
          this.planCollection = msgbus.reqres.request("get:all:plans");
          return this.planCollection.fetch({
            success: this.showPlanListView
          });
        };

        PlansListController.prototype.showPlanListView = function(planCollection) {
          var planListShowView;
          planListShowView = this.getPlanListView(planCollection);
          return this.layout.plansListRegion.show(planListShowView);
        };

        PlansListController.prototype.getPlanListView = function(planCollection) {
          return new PlanListView.PlanListsView({
            collection: planCollection,
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

}).call(this);
