(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['app', 'regioncontroller', 'apps/user-domains/groups/add/add-group-view', 'msgbus'], function(App, RegionController, AddGroupView, msgbus) {
    return App.module("UserDomainApp.AddGroups", function(AddGroups, App, BackBone, Marionette, $, _) {
      var DomainAddGroupController;
      DomainAddGroupController = (function(_super) {
        __extends(DomainAddGroupController, _super);

        function DomainAddGroupController() {
          return DomainAddGroupController.__super__.constructor.apply(this, arguments);
        }

        DomainAddGroupController.prototype.initialize = function(opts) {
          this.domain_id = opts.domain_id;
          this.view = this.getView(this.domain_id);
          this.listenTo(this.view, "save:domain:group:clicked", this.saveDomainGroup);
          return this.show(this.view, {
            loading: true
          });
        };

        DomainAddGroupController.prototype.getView = function(domainid) {
          return new AddGroupView({
            domain_id: domainid
          });
        };

        DomainAddGroupController.prototype.saveDomainGroup = function(groupdata) {
          var domainGroupModel;
          domainGroupModel = msgbus.reqres.request("create:domain:group:model", groupdata);
          return domainGroupModel.save(null, {
            wait: true,
            success: this.domainGroupAdded
          });
        };

        DomainAddGroupController.prototype.domainGroupAdded = function(domainGroupModel) {
          this.view.triggerMethod("domain:group:added");
          return console.log(domainGroupModel);
        };

        return DomainAddGroupController;

      })(RegionController);
      return App.commands.setHandler("add:domain:groups", function(opts) {
        return new DomainAddGroupController(opts);
      });
    });
  });

}).call(this);
