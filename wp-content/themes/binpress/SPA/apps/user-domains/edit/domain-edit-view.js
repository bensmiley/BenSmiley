var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'text!apps/user-domains/templates/AddEditUserDomain.html', 'text!apps/user-domains/templates/activeSubscription.html', 'additionalmethods'], function(Marionette, addEditUserDomainTpl, activeSubscriptionTpl, additionalmethods) {
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
          this.trigger("edit:domain:clicked", domaindata);
          return $('.ajax-loader-login').show();
        }
      },
      'click #btn-delete-domain': function() {
        if (confirm("If you do this your payment plan will be cancelled and your domain will be deleted. You want to continue?")) {
          $('.ajax-loader-login').show();
          return this.trigger("delete:domain:clicked");
        }
      }
    };

    DomainEditLayout.prototype.regions = {
      groupsRegion: '#groups-region',
      activeSubscriptionRegion: '#active-subscription'
    };

    DomainEditLayout.prototype.onShow = function() {
      this.$el.find('.form-title').text('Edit Domain');
      this.$el.find('#domain-groups').css({
        'display': 'inline'
      });
      return this.$el.find('#add-edit-user-domain-form').validate(this.validationOptions());
    };

    DomainEditLayout.prototype.onDomainUpdated = function() {
      var successhtml;
      $('.ajax-loader-login').hide();
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
            domain: true
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

    ActiveSubscriptionView.prototype.template = activeSubscriptionTpl;

    ActiveSubscriptionView.prototype.className = 'alert alert-info';

    ActiveSubscriptionView.prototype.events = {
      'click #cancel-plan': function() {
        var pendingSubscription;
        if (confirm('Delete the subscription?')) {
          pendingSubscription = (this.model.get('pending_subscription')).subscription_id;
          return this.trigger("delete:pending:subscription", pendingSubscription);
        }
      }
    };

    ActiveSubscriptionView.prototype.onShow = function() {
      if (!_.isUndefined(this.model.get('pending_subscription'))) {
        this.$el.find('#change-plan').hide();
        this.$el.find('.text-muted').hide();
        return this.$el.find('#pending-subscription').show();
      }
    };

    ActiveSubscriptionView.prototype.serializeData = function() {
      var data;
      data = ActiveSubscriptionView.__super__.serializeData.call(this);
      data.active_plan_name = (this.model.get('active_subscription')).plan_name;
      data.active_plan_price = (this.model.get('active_subscription')).price;
      data.active_bill_start = (this.model.get('active_subscription')).bill_start;
      data.active_bill_end = (this.model.get('active_subscription')).bill_end;
      if (!_.isUndefined(this.model.get('pending_subscription'))) {
        data.pending_plan_name = (this.model.get('pending_subscription')).plan_name;
        data.pending_plan_price = (this.model.get('pending_subscription')).price;
        data.pending_start_date = (this.model.get('pending_subscription')).start_date;
      }
      return data;
    };

    return ActiveSubscriptionView;

  })(Marionette.ItemView);
  return {
    DomainEditLayout: DomainEditLayout,
    ActiveSubscriptionView: ActiveSubscriptionView
  };
});
