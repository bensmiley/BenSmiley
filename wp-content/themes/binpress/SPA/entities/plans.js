var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['backbone', 'msgbus'], function(Backbone, msgbus) {
  var API, PlanCollection, PlanModel;
  PlanModel = (function(_super) {
    __extends(PlanModel, _super);

    function PlanModel() {
      return PlanModel.__super__.constructor.apply(this, arguments);
    }

    PlanModel.prototype.name = 'plan';

    PlanModel.prototype.idAttribute = 'plan_id';

    return PlanModel;

  })(Backbone.Model);
  PlanCollection = (function(_super) {
    __extends(PlanCollection, _super);

    function PlanCollection() {
      return PlanCollection.__super__.constructor.apply(this, arguments);
    }

    PlanCollection.prototype.model = PlanModel;

    PlanCollection.prototype.url = function() {
      return "" + AJAXURL + "?action=fetch-all-plans";
    };

    return PlanCollection;

  })(Backbone.Collection);
  API = {
    getAllPlans: function() {
      var planCollection;
      planCollection = new PlanCollection;
      return planCollection;
    },
    getPlanByPlanId: function(planId) {
      var planModel;
      planModel = new PlanModel({
        'plan_id': planId
      });
      planModel.fetch();
      return planModel;
    }
  };
  msgbus.reqres.setHandler("get:all:plans", function() {
    return API.getAllPlans();
  });
  msgbus.reqres.setHandler("get:plan:by:planid", function(planId) {
    return API.getPlanByPlanId(planId);
  });
  return {
    PlanModel: PlanModel,
    PlanCollection: PlanCollection
  };
});
