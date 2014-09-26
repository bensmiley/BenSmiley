var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['backbone', 'msgbus'], function(Backbone, msgbus) {
  var API, UserBillingModel, UserModel, currentUser;
  UserModel = (function(_super) {
    __extends(UserModel, _super);

    function UserModel() {
      return UserModel.__super__.constructor.apply(this, arguments);
    }

    UserModel.prototype.name = 'user';

    UserModel.prototype.idAttribute = 'ID';

    return UserModel;

  })(Backbone.Model);
  CURRENTUSERDATA['ID'] = parseInt(CURRENTUSERDATA['ID']);
  currentUser = new UserModel;
  currentUser.set(CURRENTUSERDATA);
  UserBillingModel = (function(_super) {
    __extends(UserBillingModel, _super);

    function UserBillingModel() {
      return UserBillingModel.__super__.constructor.apply(this, arguments);
    }

    UserBillingModel.prototype.name = 'user-payment';

    UserBillingModel.prototype.idAttribute = 'ID';

    return UserBillingModel;

  })(Backbone.Model);
  API = {
    getCurrentUser: function() {
      return currentUser;
    },
    getCurrentUserId: function() {
      return currentUser.get('ID');
    },
    getUserBillingData: function() {
      var userBillingModel;
      userBillingModel = new UserBillingModel({
        'ID': CURRENTUSERDATA.ID
      });
      return userBillingModel;
    },
    getUserById: function(userId) {
      var userModel;
      userModel = {};
      if (currentUser.get('ID') === userId) {
        userModel = currentUser;
      } else {
        userModel = new UserModel({
          ID: userId
        });
        userModel.fetch();
      }
      return userModel;
    }
  };
  msgbus.reqres.setHandler("get:current:user:model", function() {
    return API.getCurrentUser();
  });
  msgbus.reqres.setHandler("get:user:model", function(userId) {
    return API.getUserById(userId);
  });
  msgbus.reqres.setHandler("get:current:user:id", function() {
    return API.getCurrentUserId();
  });
  msgbus.reqres.setHandler("get:user:billing:data", function() {
    return API.getUserBillingData();
  });
  return UserModel;
});
