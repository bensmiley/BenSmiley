var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['backbone', 'msgbus'], function(Backbone, msgbus) {
  var API, UserDomainCollection, UserDomainModel;
  UserDomainModel = (function(_super) {
    __extends(UserDomainModel, _super);

    function UserDomainModel() {
      return UserDomainModel.__super__.constructor.apply(this, arguments);
    }

    UserDomainModel.prototype.name = 'user-domain';

    UserDomainModel.prototype.idAttribute = 'ID';

    UserDomainModel.prototype.defaults = function() {
      return {
        user_id: CURRENTUSERDATA.ID
      };
    };

    return UserDomainModel;

  })(Backbone.Model);
  UserDomainCollection = (function(_super) {
    __extends(UserDomainCollection, _super);

    function UserDomainCollection() {
      return UserDomainCollection.__super__.constructor.apply(this, arguments);
    }

    UserDomainCollection.prototype.model = UserDomainModel;

    UserDomainCollection.prototype.url = function() {
      return "" + AJAXURL + "?action=fetch-user-domains";
    };

    return UserDomainCollection;

  })(Backbone.Collection);
  API = {
    getCurrentUserDomains: function() {
      var userDomainCollection;
      userDomainCollection = new UserDomainCollection;
      userDomainCollection.fetch();
      return userDomainCollection;
    },
    createCurrentUserDomainModel: function(data) {
      var userDomainModel;
      userDomainModel = new UserDomainModel(data);
      return userDomainModel;
    },
    getDomainById: function(domainId) {
      var domainModel;
      domainModel = new UserDomainModel({
        'ID': parseInt(domainId)
      });
      domainModel.fetch();
      return domainModel;
    }
  };
  msgbus.reqres.setHandler("get:current:user:domains", function() {
    return API.getCurrentUserDomains();
  });
  msgbus.reqres.setHandler("get:domain:model:by:id", function(domainId) {
    return API.getDomainById(domainId);
  });
  msgbus.reqres.setHandler("create:domain:model", function(data) {
    return API.createCurrentUserDomainModel(data);
  });
  return {
    UserDomainModel: UserDomainModel,
    UserDomainCollection: UserDomainCollection
  };
});
