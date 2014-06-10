// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'msgbus', 'apps/user-domains/show/user-domains-view', 'apps/user-domains/add/user-domain-add-controller'], function(App, AppController, msgbus, View) {
  return App.module("UserDomainApp", function(UserDomainApp, App, BackBone, Marionette, $, _) {
    var UserDomainController;
    UserDomainController = (function(_super) {
      __extends(UserDomainController, _super);

      function UserDomainController() {
        return UserDomainController.__super__.constructor.apply(this, arguments);
      }

      UserDomainController.prototype.initialize = function(opts) {
        this.layout = this.getLayout();
        this.listenTo(this.layout, "show", function() {
          var userDomainsCollection;
          userDomainsCollection = msgbus.reqres.request("get:current:user:domains");
          userDomainsCollection.fetch();
          return this.layout.domainListRegion.show(this.getDomainListView(userDomainsCollection));
        });
        this.listenTo(this.layout, "add:user:domain:clicked", function() {
          return App.execute("add:user:domain", {
            region: this.layout.domainListRegion
          });
        });
        return this.show(this.layout);
      };

      UserDomainController.prototype.getLayout = function() {
        return new View.UserDomainView;
      };

      UserDomainController.prototype.getDomainListView = function(userDomainsCollection) {
        return new View.DomainListView({
          collection: userDomainsCollection
        });
      };

      return UserDomainController;

    })(AppController);
    return App.commands.setHandler("show:user:domains", function(opts) {
      return new UserDomainController(opts);
    });
  });
});
