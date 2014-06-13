// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'msgbus', 'apps/plans/list/list-plans-controller'], function(App, msgbus) {
  return App.module('PlansApp', function(PlansApp, App, Backbone, Marionette, $, _) {
    var API, PlansAppRouter;
    PlansAppRouter = (function(_super) {
      __extends(PlansAppRouter, _super);

      function PlansAppRouter() {
        return PlansAppRouter.__super__.constructor.apply(this, arguments);
      }

      PlansAppRouter.prototype.appRoutes = {
        'change-plan': 'show'
      };

      return PlansAppRouter;

    })(Marionette.AppRouter);
    API = {
      show: function() {
        return App.execute("show:plans:list", {
          region: App.mainContentRegion
        });
      }
    };
    return PlansApp.on({
      'start': function() {
        return new PlansAppRouter({
          controller: API
        });
      }
    });
  });
});