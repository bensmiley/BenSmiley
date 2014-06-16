// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'text!apps/user-profile/templates/userprofile.html'], function(Marionette, userProfileTpl) {
  var UserProfileView;
  UserProfileView = (function(_super) {
    __extends(UserProfileView, _super);

    function UserProfileView() {
      return UserProfileView.__super__.constructor.apply(this, arguments);
    }

    UserProfileView.prototype.className = 'user-profile-container';

    UserProfileView.prototype.template = userProfileTpl;

    UserProfileView.prototype.tagName = 'form';

    UserProfileView.prototype.id = "user-profile-form";

    UserProfileView.prototype.regions = {
      userPhotoRegion: '#user-photo'
    };

    UserProfileView.prototype.events = {
      'click #btn-save-user-profile': function() {
        var userdata;
        if (this.$el.valid()) {
          userdata = Backbone.Syphon.serialize(this);
          return this.trigger("save:user:profile:clicked", userdata);
        }
      }
    };

    UserProfileView.prototype.onShow = function() {
      return this.$el.validate(this.validationOptions());
    };

    UserProfileView.prototype.validationOptions = function() {
      return {
        rules: {
          display_name: {
            required: true
          },
          user_email: {
            required: true,
            email: true
          },
          user_pass: {
            minlength: 5
          },
          confirm_password: {
            equalTo: "#user_pass"
          }
        },
        messages: {
          user_name: 'Enter valid user name'
        }
      };
    };

    UserProfileView.prototype.onUserProfileUpdated = function() {
      var msg, userPassword;
      this.$el.find('#form-msg').empty();
      userPassword = this.$el.find('#user_pass').val();
      if (userPassword !== "") {
        msg = "<p>Updated User profile</p> <p>Logout of your account</p>";
      } else {
        msg = "<p>Updated User profile</p>";
      }
      return this.$el.find('#form-msg').append(msg);
    };

    return UserProfileView;

  })(Marionette.Layout);
  return UserProfileView;
});
