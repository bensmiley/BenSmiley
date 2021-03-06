var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'msgbus', 'apps/user-profile/edit/user-profile-controller'], function(App, msgbus) {
  return App.module('UserProfileApp', function(UserProfileApp, App, Backbone, Marionette, $, _) {
    var API, UserProfileAppRouter;
    UserProfileAppRouter = (function(_super) {
      __extends(UserProfileAppRouter, _super);

      function UserProfileAppRouter() {
        return UserProfileAppRouter.__super__.constructor.apply(this, arguments);
      }

      UserProfileAppRouter.prototype.appRoutes = {
        'profile': 'show'
      };

      return UserProfileAppRouter;

    })(Marionette.AppRouter);
    API = {
      show: function() {
        var params;
        params = {
          region: App.mainContentRegion
        };
        return App.execute("show:user:profile", params);
      }
    };
    return UserProfileApp.on({
      'start': function() {
        return new UserProfileAppRouter({
          controller: API
        });
      }
    });
  });
});
