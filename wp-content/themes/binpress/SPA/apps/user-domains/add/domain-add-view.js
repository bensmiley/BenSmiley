// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'text!apps/user-domains/templates/AddEditUserDomain.html'], function(Marionette, addUserDomainTpl) {
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
          return this.trigger("add:domain:clicked", domaindata);
        }
      },
      'click #show-domain-list': function() {
        return this.trigger("show:domain:list:clicked");
      }
    };

    DomainAddView.prototype.onShow = function() {
      this.$el.find('#add-edit-user-domain-form').validate(this.validationOptions());
      this.$el.find('#form-title').text('Add Domain');
      return this.$el.find('#tabs').hide();
    };

    DomainAddView.prototype.onUserDomainAdded = function() {
      var successhtml;
      this.$el.find('#btn-reset-add-domain').click();
      this.$el.find('#msg').empty();
      successhtml = '<div class="alert alert-success"> <button class="close" data-dismiss="alert">&times;</button> Domain Sucessfully Added </div>';
      return this.$el.find('#msg').append(successhtml);
    };

    DomainAddView.prototype.validationOptions = function() {
      return {
        rules: {
          post_title: {
            required: true
          },
          domain_url: {
            required: true,
            url: true
          }
        },
        messages: {
          domain_url: 'Enter valid url'
        }
      };
    };

    return DomainAddView;

  })(Marionette.ItemView);
  return DomainAddView;
});
