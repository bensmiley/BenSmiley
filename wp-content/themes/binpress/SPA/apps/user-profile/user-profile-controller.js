// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'region-controller', 'apps/user-profile/user-profile-view'], function(App, AppController, View) {
  return App.module("UserProfileApp", function(UserProfileApp, App, BackBone, Marionette, $, _) {
    var UserProfileController;
    UserProfileController = (function(_super) {
      __extends(UserProfileController, _super);

      function UserProfileController() {
        return UserProfileController.__super__.constructor.apply(this, arguments);
      }

      UserProfileController.prototype.initialize = function(opts) {
        var usermodel;
        this.usermodel = usermodel = App.request("get:user:model");
        this.layout = this.getLayout(this.usermodel);
        this.listenTo(this.layout, "show", function() {
          return App.execute("start:upload:app", {
            region: this.layout.userPhotoRegion
          });
        });
        this.listenTo(this.layout, "save:user:profile:clicked", this.saveUserProfile);
        return this.show(this.layout);
      };

      UserProfileController.prototype.getLayout = function(usermodel) {
        return new View.UserProfileView({
          model: usermodel
        });
      };

      UserProfileController.prototype.saveUserProfile = function(userdata) {
        this.usermodel.set(userdata);
        return this.usermodel.save(null, {
          wait: true,
          success: this.showSuccess()
        });
      };

      UserProfileController.prototype.showSuccess = function() {
        return this.layout.triggerMethod("user:profile:updated");
      };

      return UserProfileController;

    })(AppController);
    return App.commands.setHandler("show:user:profile", function(opts) {
      return new UserProfileController(opts);
    });
  });
});
