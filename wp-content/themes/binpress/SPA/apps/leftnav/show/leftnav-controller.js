(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['app', 'regioncontroller'], function(App, RegionController) {
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
          this.view = this.getView();
          return this.show(this.view);
        };

        Controller.prototype.getView = function() {
          return new LeftNavView;
        };

        return Controller;

      })(RegionController);
      LeftNavView = (function(_super) {
        __extends(LeftNavView, _super);

        function LeftNavView() {
          return LeftNavView.__super__.constructor.apply(this, arguments);
        }

        LeftNavView.prototype.template = '<div class="page-sidebar-wrapper" id="main-menu-wrapper"> <ul> <li class="start"> <a href="#profile" id="user-profile"> <i class="fa fa-user"></i> <span class="title">User Profile</span> <span class="selected"></span> <span class="arrow"></span> </a> </li> <li class="start"> <a href="#domains" id="user-domains"> <i class="fa fa-globe"></i> <span class="title">My Domains</span> <span class="selected"></span> <span class="arrow"></span> </a> </li> </ul> <div class="clearfix"></div> </div>';

        LeftNavView.prototype.className = 'page-sidebar';

        return LeftNavView;

      })(Marionette.CompositeView);
      return LeftNavView;
    });
  });

}).call(this);
