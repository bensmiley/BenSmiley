var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['backbone', 'msgbus'], function(Backbone, msgbus) {
  var API, GroupCollection, GroupModel;
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
  GroupCollection = (function(_super) {
    __extends(GroupCollection, _super);

    function GroupCollection() {
      return GroupCollection.__super__.constructor.apply(this, arguments);
    }

    GroupCollection.prototype.model = GroupModel;

    GroupCollection.prototype.url = function() {
      return "" + AJAXURL + "?action=fetch-groups";
    };

    return GroupCollection;

  })(Backbone.Collection);
  API = {
    createGroupModel: function(data) {
      var groupModel;
      groupModel = new GroupModel(data);
      return groupModel;
    },
    getGroupsByDomainId: function(domainId) {
      var groupCollection;
      groupCollection = new GroupCollection;
      groupCollection.fetch({
        data: {
          domain_id: domainId
        }
      });
      return groupCollection;
    }
  };
  msgbus.reqres.setHandler("create:domain:group:model", function(data) {
    return API.createGroupModel(data);
  });
  msgbus.reqres.setHandler("get:groups:for:domains", function(domainId) {
    return API.getGroupsByDomainId(domainId);
  });
  return {
    GroupModel: GroupModel,
    GroupCollection: GroupCollection
  };
});
