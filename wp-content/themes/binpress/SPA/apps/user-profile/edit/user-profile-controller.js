var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'msgbus', 'regioncontroller', 'apps/user-profile/edit/user-profile-view'], function(App, msgbus, RegionController, UserProfileView) {
  return App.module("UserProfileApp.Edit", function(Edit, App, BackBone, Marionette, $, _) {
    var UserProfileController;
    UserProfileController = (function(_super) {
      __extends(UserProfileController, _super);

      function UserProfileController() {
        this.showSuccess = __bind(this.showSuccess, this);
        return UserProfileController.__super__.constructor.apply(this, arguments);
      }

      UserProfileController.prototype.initialize = function(opts) {
        this.userModel = msgbus.reqres.request("get:current:user:model");
        this.layout = this.getLayout(this.userModel);
        this.listenTo(this.layout, "show", (function(_this) {
          return function() {
            return App.execute("start:upload:app", {
              region: _this.layout.userPhotoRegion,
              model: _this.userModel
            });
          };
        })(this));
        this.listenTo(this.layout, "save:user:profile:clicked", this.saveUserProfile);
        return this.show(this.layout);
      };

      UserProfileController.prototype.getLayout = function(userModel) {
        return new UserProfileView({
          model: userModel
        });
      };

      UserProfileController.prototype.saveUserProfile = function(userData) {
        return this.userModel.save(userData, {
          wait: true,
          success: this.showSuccess
        });
      };

      UserProfileController.prototype.showSuccess = function() {
        return this.layout.triggerMethod("user:profile:updated");
      };

      return UserProfileController;

    })(RegionController);
    return App.commands.setHandler("show:user:profile", function(options) {
      return new UserProfileController(options);
    });
  });
});
