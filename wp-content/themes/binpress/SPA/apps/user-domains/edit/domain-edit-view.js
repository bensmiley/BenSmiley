(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['marionette', 'text!apps/user-domains/templates/addEditUserDomain.html'], function(Marionette, addEditUserDomainTpl) {
    var ActiveSubscriptionView, DomainEditLayout;
    DomainEditLayout = (function(_super) {
      __extends(DomainEditLayout, _super);

      function DomainEditLayout() {
        return DomainEditLayout.__super__.constructor.apply(this, arguments);
      }

      DomainEditLayout.prototype.className = 'add-user-domain-container';

      DomainEditLayout.prototype.template = addEditUserDomainTpl;

      DomainEditLayout.prototype.events = {
        'click #btn-add-edit-user-domain': function() {
          var domaindata;
          if (this.$el.find('#add-edit-user-domain-form').valid()) {
            domaindata = Backbone.Syphon.serialize(this);
            return this.trigger("edit:domain:clicked", domaindata);
          }
        }
      };

      DomainEditLayout.prototype.regions = {
        addDomainGroupRegion: '#add-domain-groups',
        listDomainGroupRegion: '#list-domain-groups',
        activeSubscriptionRegion: '#active-subscription'
      };

      DomainEditLayout.prototype.onShow = function() {
        this.$el.find('#form-title').text('Edit Domain');
        this.$el.find('#domain-groups').css({
          'display': 'inline'
        });
        return this.$el.find('#add-edit-user-domain-form').validate(this.validationOptions());
      };

      DomainEditLayout.prototype.onDomainUpdated = function() {
        var successhtml;
        this.$el.find('#btn-reset-add-domain').click();
        this.$el.find('#msg').empty();
        successhtml = "<div class='alert alert-success'> <button class='close' data-dismiss='alert'>&times;</button> Domain Updated Sucessfully<div>";
        return this.$el.find('#msg').append(successhtml);
      };

      DomainEditLayout.prototype.validationOptions = function() {
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

      return DomainEditLayout;

    })(Marionette.Layout);
    ActiveSubscriptionView = (function(_super) {
      __extends(ActiveSubscriptionView, _super);

      function ActiveSubscriptionView() {
        return ActiveSubscriptionView.__super__.constructor.apply(this, arguments);
      }

      ActiveSubscriptionView.prototype.template = '<h3 class="m-b-20"><span class="semi-bold">Plans Details</span></h3> <div class="grid simple"> <dl class="dl-horizontal dl-plan"> <dt>Current Plan :</dt> <dd><span class="label label-info">{{name}}</span></dd> <dt>Payement :</dt> <dd>{{price}}/month</dd> <dt>Billing Cycle :</dt> <dd>{{bill_start}} To {{bill_end}}</dd> </dl> <a href="#domains/edit/{{domain_id}}/list-plan" class="btn btn-success btn-block"> <i class="icon-ok"></i> Change Plan</a> <div class="clearfix"></div> </div>';

      ActiveSubscriptionView.prototype.className = 'alert alert-info';

      return ActiveSubscriptionView;

    })(Marionette.ItemView);
    return {
      DomainEditLayout: DomainEditLayout,
      ActiveSubscriptionView: ActiveSubscriptionView
    };
  });

}).call(this);
