var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'apps/user-domains/groups/show/show-group-view', 'msgbus'], function(App, RegionController, ShowGroupView, msgbus) {
  return App.module("UserDomainApp.ShowGroups", function(ShowGroups, App, BackBone, Marionette, $, _) {
    var ShowGroupController;
    ShowGroupController = (function(_super) {
      __extends(ShowGroupController, _super);

      function ShowGroupController() {
        this.groupDeleted = __bind(this.groupDeleted, this);
        this.groupUpdated = __bind(this.groupUpdated, this);
        this.updateDomainGroup = __bind(this.updateDomainGroup, this);
        this.domainGroupAdded = __bind(this.domainGroupAdded, this);
        this.getView = __bind(this.getView, this);
        this.onGroupCollectionFetched = __bind(this.onGroupCollectionFetched, this);
        return ShowGroupController.__super__.constructor.apply(this, arguments);
      }

      ShowGroupController.prototype.initialize = function(opts) {
        this.domainId = opts.domain_id;
        this.groupCollection = msgbus.reqres.request("get:groups:for:domains", this.domainId);
        return msgbus.commands.execute("when:fetched", this.groupCollection, (function(_this) {
          return function() {
            return _this.onGroupCollectionFetched(_this.groupCollection);
          };
        })(this));
      };

      ShowGroupController.prototype.onGroupCollectionFetched = function(groupCollection) {
        this.view = this.getView(groupCollection);
        this.listenTo(this.view, "delete:group:clicked", this.deleteGroup);
        this.listenTo(this.view, "save:domain:group:clicked", this.saveDomainGroup);
        this.listenTo(this.view, "update:domain:group:clicked", this.updateDomainGroup);
        return this.show(this.view, {
          loading: true
        });
      };

      ShowGroupController.prototype.getView = function(groupCollection) {
        return new ShowGroupView({
          collection: groupCollection,
          domainId: this.domainId
        });
      };

      ShowGroupController.prototype.saveDomainGroup = function(groupData) {
        var domainGroupModel;
        domainGroupModel = msgbus.reqres.request("create:domain:group:model", groupData);
        return domainGroupModel.save(null, {
          wait: true,
          success: this.domainGroupAdded
        });
      };

      ShowGroupController.prototype.domainGroupAdded = function(domainGroupModel) {
        this.groupCollection.add(domainGroupModel);
        return this.view.triggerMethod("domain:group:added");
      };

      ShowGroupController.prototype.updateDomainGroup = function(groupData, editModel) {
        editModel.set(groupData);
        return editModel.save(null, {
          wait: true,
          success: this.groupUpdated
        });
      };

      ShowGroupController.prototype.groupUpdated = function() {
        return this.view.triggerMethod("group:updated");
      };

      ShowGroupController.prototype.deleteGroup = function(groupModel) {
        groupModel.set({
          'domain_id': this.domainId
        });
        return groupModel.destroy({
          allData: true,
          wait: true,
          success: this.groupDeleted
        });
      };

      ShowGroupController.prototype.groupDeleted = function() {
        return this.view.triggerMethod("group:deleted");
      };

      return ShowGroupController;

    })(RegionController);
    return App.commands.setHandler("show:domain:groups", function(opts) {
      return new ShowGroupController(opts);
    });
  });
});
