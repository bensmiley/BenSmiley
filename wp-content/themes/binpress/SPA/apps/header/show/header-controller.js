(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['app', 'regioncontroller', 'behaviors/closewarn', 'msgbus'], function(App, AppController, CloseWarn, msgbus) {
    return App.module('HeaderApp.Show', function(Show, App, Backbone, Marionette, $, _) {
      var HeaderLayout, UserDisplayView;
      Show.Controller = (function(_super) {
        __extends(Controller, _super);

        function Controller() {
          this.handleUserUpdate = __bind(this.handleUserUpdate, this);
          return Controller.__super__.constructor.apply(this, arguments);
        }

        Controller.prototype.initialize = function(opt) {
          if (opt == null) {
            opt = {};
          }
          this.layout = this.getLayout();
          this.listenTo(this.layout, "show", this.showUserDisplayView);
          return this.show(this.layout);
        };

        Controller.prototype.getLayout = function() {
          return new HeaderLayout;
        };

        Controller.prototype.showUserDisplayView = function() {
          this.userModel = msgbus.reqres.request("get:current:user:model");
          this.listenTo(this.userModel, "change", this.handleUserUpdate);
          this.userDisplayView = this.getUserDisplayView(this.userModel);
          this.layout.userDisplayRegion.show(this.userDisplayView);
          return this.listenTo(this.userDisplayView, "logout:clicked", this.logoutUser);
        };

        Controller.prototype.getUserDisplayView = function(userModel) {
          return new UserDisplayView({
            model: userModel
          });
        };

        Controller.prototype.handleUserUpdate = function(userModel) {
          return this.userDisplayView.triggerMethod("update:user:display", userModel);
        };

        Controller.prototype.logoutUser = function() {
          var options;
          options = {
            url: AJAXURL,
            method: 'POST',
            data: {
              action: 'user-logout'
            }
          };
          return $.ajax(options).done(function(response) {
            return window.location.href = "" + response.redirect_url + "/home";
          }).fail(function(resp) {
            return console.log('error');
          });
        };

        return Controller;

      })(AppController);
      HeaderLayout = (function(_super) {
        __extends(HeaderLayout, _super);

        function HeaderLayout() {
          return HeaderLayout.__super__.constructor.apply(this, arguments);
        }

        HeaderLayout.prototype.template = '<div class="navbar-inner"> <div class=""> <div class="pull-left"> <a href="index.html"> <h3 class="p-l-20 text-white">Logo</h3></a> </div> <div id="user-display"></div> </div> </div>';

        HeaderLayout.prototype.className = 'header navbar navbar-inverse';

        HeaderLayout.prototype.regions = {
          userDisplayRegion: '#user-display'
        };

        return HeaderLayout;

      })(Marionette.Layout);
      HeaderLayout;
      UserDisplayView = (function(_super) {
        __extends(UserDisplayView, _super);

        function UserDisplayView() {
          return UserDisplayView.__super__.constructor.apply(this, arguments);
        }

        UserDisplayView.prototype.template = '<div class="user-profile pull-left m-t-10"> <img src="{{user_photo}}" alt="" width="35" height="35" id="user-photo"> </div> <ul class="nav quick-section "> <li class="quicklinks"> <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options"> <div class="pull-left"> <span class="bold display_name">{{display_name}}</span> </div> <div class="iconset top-down-arrow pull-left m-t-5 m-l-10"></div> </a> <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options"> <li> <a href="#logout" id="logout"> <i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out </a> </li> </ul> </li> </ul>';

        UserDisplayView.prototype.className = 'pull-right';

        UserDisplayView.prototype.events = {
          'click #logout': function() {
            return this.trigger("logout:clicked");
          }
        };

        UserDisplayView.prototype.onUpdateUserDisplay = function(userModel) {
          var displayName, userPhoto;
          displayName = userModel.get('display_name');
          userPhoto = userModel.get('user_photo');
          this.$el.find('.display_name').text(displayName);
          return this.$el.find('#user-photo').attr({
            "src": userPhoto
          });
        };

        return UserDisplayView;

      })(Marionette.ItemView);
      return UserDisplayView;
    });
  });

}).call(this);
