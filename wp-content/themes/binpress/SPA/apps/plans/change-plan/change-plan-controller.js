// Generated by CoffeeScript 1.7.1
var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'apps/plans/change-plan/change-plan-view', 'msgbus'], function(App, RegionController, ChangePlanView, msgbus) {
  return App.module("PlansApp.Change", function(Change, App, BackBone, Marionette, $, _) {
    var ChangePlanController;
    ChangePlanController = (function(_super) {
      __extends(ChangePlanController, _super);

      function ChangePlanController() {
        this.planIDModelFetched = __bind(this.planIDModelFetched, this);
        return ChangePlanController.__super__.constructor.apply(this, arguments);
      }

      ChangePlanController.prototype.initialize = function(opts) {
        var planIdModel;
        this.domainId = opts.domainID;
        this.layout = this.getLayout();
        this.show(this.layout);
        planIdModel = msgbus.reqres.request("get:current:plan:id", this.domainId);
        return planIdModel.fetch({
          data: {
            'domain_id': this.domainId,
            'action': 'get-current-domain-plan-id'
          },
          success: this.planIDModelFetched
        });
      };

      ChangePlanController.prototype.planIDModelFetched = function(planIdModel) {
        return this.layout = this.getLayout(planIdModel);
      };

      ChangePlanController.prototype.getLayout = function() {
        return new ChangePlanView.ChangePlanLayout;
      };

      return ChangePlanController;

    })(RegionController);
    return App.commands.setHandler("change:plan", function(opts) {
      return new ChangePlanController(opts);
    });
  });
});