// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['backbone', 'msgbus'], function(Backbone, msgbus) {
  var API, GroupModel;
  GroupModel = (function(_super) {
    __extends(GroupModel, _super);

    function GroupModel() {
      return GroupModel.__super__.constructor.apply(this, arguments);
    }

    GroupModel.prototype.name = 'domain-group';

    GroupModel.prototype.idAttribute = 'ID';

    GroupModel.prototype.defaults = function() {
      return {
        domain_id: 0
      };
    };

    return GroupModel;

  })(Backbone.Model);
  API = {
    createGroupModel: function(data) {
      var groupModel;
      groupModel = new GroupModel(data);
      return groupModel;
    }
  };
  msgbus.reqres.setHandler("create:domain:group:model", function(data) {
    return API.createGroupModel(data);
  });
  return GroupModel;
});