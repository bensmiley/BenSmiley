var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'msgbus', 'apps/user-domains/list/user-domains-list-controller', 'apps/user-domains/edit/domain-edit-controller', 'apps/user-domains/add/domain-add-controller'], function(App, msgbus) {
  return App.module('UserDomainApp', function(UserDomainApp, App, Backbone, Marionette, $, _) {
    var API, UserDomainAppRouter;
    UserDomainAppRouter = (function(_super) {
      __extends(UserDomainAppRouter, _super);

      function UserDomainAppRouter() {
        return UserDomainAppRouter.__super__.constructor.apply(this, arguments);
      }

      UserDomainAppRouter.prototype.appRoutes = {
        'domains': 'list',
        'domains/add': 'add',
        'domains/edit/:id': 'edit'
      };

      return UserDomainAppRouter;

    })(Marionette.AppRouter);
    API = {
      list: function() {
        return App.execute("list:user:domains", {
          region: App.mainContentRegion
        });
      },
      add: function() {
        return App.execute("add:user:domains", {
          region: App.mainContentRegion
        });
      },
      edit: function(id) {
        return App.execute("edit:user:domain", {
          region: App.mainContentRegion,
          domainId: id
        });
      }
    };
    return UserDomainApp.on({
      'start': function() {
        return new UserDomainAppRouter({
          controller: API
        });
      }
    });
  });
});
