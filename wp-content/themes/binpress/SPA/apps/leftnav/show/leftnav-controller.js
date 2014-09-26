var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

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
        this.setHighlighted = __bind(this.setHighlighted, this);
        return LeftNavView.__super__.constructor.apply(this, arguments);
      }

      LeftNavView.prototype.template = '<div class="page-sidebar-wrapper" id="main-menu-wrapper"> <ul> <li class="start active"> <a href="#domains" id="user-domains"> <i class="fa fa-globe"></i> <span class="title">My Domains</span> <span class="selected"></span> <span class="arrow"></span> </a> </li> <li class="start"> <a href="#profile" id="user-profile"> <i class="fa fa-user"></i> <span class="title">User Profile</span> <span class="selected"></span> <span class="arrow"></span> </a> </li> </ul> <div class="clearfix"></div> </div>';

      LeftNavView.prototype.className = 'page-sidebar';

      LeftNavView.prototype.events = {
        'click ul > li': 'highlightActive'
      };

      LeftNavView.prototype.initialize = function(opt) {
        this.listenTo(App.vent, 'app:started', this.setHighlighted);
        return LeftNavView.__super__.initialize.call(this, opt);
      };

      LeftNavView.prototype.highlightActive = function(evt) {
        $(evt.currentTarget).siblings().removeClass('active');
        return $(evt.currentTarget).addClass('active');
      };

      LeftNavView.prototype.setHighlighted = function() {
        var route;
        route = App.getCurrentRoute();
        console.log(route);
        return this.$el.find("a[href='#" + route + "']").click();
      };

      return LeftNavView;

    })(Marionette.CompositeView);
    return LeftNavView;
  });
});
