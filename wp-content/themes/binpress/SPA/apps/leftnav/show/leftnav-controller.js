// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'region-controller'], function(App, AppController) {
  return App.module('LeftNavApp.Show', function(Show, App, Backbone, Marionette, $, _) {
    var LeftNavView;
    Show.Controller = (function(_super) {
      __extends(Controller, _super);

      function Controller() {
        return Controller.__super__.constructor.apply(this, arguments);
      }

      Controller.prototype.initialize = function(opt) {
        if (opt == null) {
          opt = {};
        }
        this.layout = this.getLayout();
        this.listenTo(this.layout, "user:profile:clicked", function() {
          return App.execute("show:user:profile", {
            region: App.mainContentRegion
          });
        });
        return this.show(this.layout);
      };

      Controller.prototype.getLayout = function() {
        return new LeftNavView;
      };

      return Controller;

    })(AppController);
    return LeftNavView = (function(_super) {
      __extends(LeftNavView, _super);

      function LeftNavView() {
        return LeftNavView.__super__.constructor.apply(this, arguments);
      }

      LeftNavView.prototype.template = '<div class="page-sidebar-wrapper" id="main-menu-wrapper"> <ul> <li class="start"> <a href="javascript:void(0)" id="user-profile"> <i class="fa fa-user"></i> <span class="title">User Profile</span> <span class="selected"></span> <span class="arrow"></span> </a> </li> </ul> <div class="clearfix"></div> </div>';

      LeftNavView.prototype.className = 'page-sidebar';

      LeftNavView.prototype.id = 'main-menu';

      LeftNavView.prototype.events = {
        'click #user-profile': function() {
          return this.trigger("user:profile:clicked");
        }
      };

      return LeftNavView;

    })(Marionette.Layout);
  });
});