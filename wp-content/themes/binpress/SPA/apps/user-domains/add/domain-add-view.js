var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'text!apps/user-domains/templates/AddEditUserDomain.html', 'additionalmethods'], function(Marionette, addUserDomainTpl, additionalmethods) {
  var DomainAddView;
  DomainAddView = (function(_super) {
    __extends(DomainAddView, _super);

    function DomainAddView() {
      return DomainAddView.__super__.constructor.apply(this, arguments);
    }

    DomainAddView.prototype.className = 'add-user-domain-container';

    DomainAddView.prototype.template = addUserDomainTpl;

    DomainAddView.prototype.events = {
      'click #btn-add-edit-user-domain': function() {
        var domaindata;
        if (this.$el.find('#add-edit-user-domain-form').valid()) {
          domaindata = Backbone.Syphon.serialize(this);
          this.trigger("add:domain:clicked", domaindata);
          return $('.ajax-loader-login').show();
        }
      }
    };

    DomainAddView.prototype.onShow = function() {
      this.$el.find('#add-edit-user-domain-form').validate(this.validationOptions());
      this.$el.find('.form-title').text('Add Domain');
      this.$el.find('#tabs').hide();
      this.$el.find('#btn-delete-domain').hide();
      return this.$el.find('#apikey-box').hide();
    };

    DomainAddView.prototype.onUserDomainAdded = function(domainId) {
      var mainUrl, redirect_url, successhtml;
      this.$el.find('#btn-reset-add-domain').click();
      $('.ajax-loader-login').hide();
      this.$el.find('#msg').empty();
      successhtml = '<div class="alert alert-success"> <button class="close" data-dismiss="alert">&times;</button> Domain Sucessfully Added </div>';
      this.$el.find('#msg').append(successhtml);
      mainUrl = window.location.href.replace(Backbone.history.getFragment(), '');
      redirect_url = "" + mainUrl + "domains/edit/" + domainId;
      return _.delay(function() {
        return window.location.href = redirect_url;
      }, 1000);
    };

    DomainAddView.prototype.onUserDomainAddError = function(errorMsg) {
      var successhtml;
      $('.ajax-loader-login').hide();
      this.$el.find('#msg').empty();
      successhtml = "<div class='alert alert-error'> <button class='close' data-dismiss='alert'>&times;</button> " + errorMsg + " </div>";
      return this.$el.find('#msg').append(successhtml);
    };

    DomainAddView.prototype.validationOptions = function() {
      return {
        rules: {
          post_title: {
            required: true
          },
          domain: {
            required: true,
            domain: true
          }
        },
        messages: {
          domain: 'Invalid domain name'
        }
      };
    };

    return DomainAddView;

  })(Marionette.ItemView);
  return DomainAddView;
});
