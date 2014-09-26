var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['backbone', 'msgbus'], function(Backbone, msgbus) {
  var API, SubscriptionModel;
  SubscriptionModel = (function(_super) {
    __extends(SubscriptionModel, _super);

    function SubscriptionModel() {
      return SubscriptionModel.__super__.constructor.apply(this, arguments);
    }

    SubscriptionModel.prototype.name = 'subscription';

    SubscriptionModel.prototype.idAttribute = 'domain_id';

    return SubscriptionModel;

  })(Backbone.Model);
  API = {
    getSubscriptionByDomainId: function(domainId) {
      var subscriptionModel;
      subscriptionModel = new SubscriptionModel({
        'domain_id': parseInt(domainId)
      });
      return subscriptionModel;
    }
  };
  msgbus.reqres.setHandler("get:subscription:for:domain", function(domainId) {
    return API.getSubscriptionByDomainId(domainId);
  });
  return SubscriptionModel;
});
