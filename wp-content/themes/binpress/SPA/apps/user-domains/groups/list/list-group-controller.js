(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['app', 'regioncontroller', 'apps/user-domains/groups/list/list-group-view', 'msgbus'], function(App, RegionController, ListGroupView, msgbus) {
    return App.module("UserDomainApp.ListGroups", function(ListGroups, App, BackBone, Marionette, $, _) {
      var DomainListGroupController;
      DomainListGroupController = (function(_super) {
        __extends(DomainListGroupController, _super);

        function DomainListGroupController() {
          return DomainListGroupController.__super__.constructor.apply(this, arguments);
        }

        DomainListGroupController.prototype.initialize = function(opts) {
          this.domain_id = opts.domain_id;
          this.view = this.getView(this.domain_id);
          return this.show(this.view, {
            loading: true
          });
        };

        DomainListGroupController.prototype.getView = function(domainid) {
          return new ListGroupView({
            domain_id: domainid
          });
        };

        return DomainListGroupController;

      })(RegionController);
      return App.commands.setHandler("list:domain:groups", function(opts) {
        return new DomainListGroupController(opts);
      });
    });
  });

}).call(this);
