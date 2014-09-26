var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'msgbus', 'apps/user-domains/list/user-domains-list-view'], function(App, RegionController, msgbus, View) {
  return App.module("UserDomainApp.List", function(List, App, BackBone, Marionette, $, _) {
    var UserDomainListController;
    UserDomainListController = (function(_super) {
      __extends(UserDomainListController, _super);

      function UserDomainListController() {
        return UserDomainListController.__super__.constructor.apply(this, arguments);
      }

      UserDomainListController.prototype.initialize = function(opts) {
        this.userDomainsCollection = msgbus.reqres.request("get:current:user:domains");
        return msgbus.commands.execute("when:fetched", this.userDomainsCollection, (function(_this) {
          return function() {
            return _this.showDomainListView();
          };
        })(this));
      };

      UserDomainListController.prototype.showDomainListView = function() {
        this.domainListView = this.getDomainListView(this.userDomainsCollection);
        return this.show(this.domainListView, {
          loading: true
        });
      };

      UserDomainListController.prototype.getDomainListView = function(userDomainsCollection) {
        return new View.DomainListView({
          collection: userDomainsCollection
        });
      };

      return UserDomainListController;

    })(RegionController);
    return App.commands.setHandler("list:user:domains", function(opts) {
      return new UserDomainListController(opts);
    });
  });
});
