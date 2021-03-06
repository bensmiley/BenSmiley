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
          this.$el.find('.ajax-loader-login').show();
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
      this.$el.find('.ajax-loader-login').hide();
      this.$el.find('#form-msg').empty();
      userPassword = this.$el.find('#user_pass').val();
      if (userPassword !== "") {
        msg = '<div class="alert alert-success"> <button class="close" data-dismiss="alert"></button> Your user profile updated sucessfully please logout from the account </div>';
      } else {
        msg = '<div class="alert alert-success"> <button class="close" data-dismiss="alert"></button> Updated User profile</div>';
      }
      this.$el.find('#form-msg').append(msg);
      return this.$el.find('.upload-success').hide();
    };

    return UserProfileView;

  })(Marionette.Layout);
  return UserProfileView;
});
