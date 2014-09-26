var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'apps/user-domains/edit/domain-edit-view', 'msgbus', 'apps/user-domains/groups/show/show-group-controller'], function(App, RegionController, EditDomainView, msgbus) {
  return App.module("UserDomainApp.Edit", function(Edit, App, BackBone, Marionette, $, _) {
    var DomainEditController;
    DomainEditController = (function(_super) {
      __extends(DomainEditController, _super);

      function DomainEditController() {
        this.domainUpdated = __bind(this.domainUpdated, this);
        this.editDomain = __bind(this.editDomain, this);
        this.showActiveSubscription = __bind(this.showActiveSubscription, this);
        this.showEditView = __bind(this.showEditView, this);
        return DomainEditController.__super__.constructor.apply(this, arguments);
      }

      DomainEditController.prototype.initialize = function(opts) {
        this.domainId = opts.domainId;
        this.domainModel = msgbus.reqres.request("get:domain:model:by:id", this.domainId);
        msgbus.commands.execute("when:fetched", this.domainModel, function() {});
        this.showEditView(this.domainModel);
        this.subscriptionModel = msgbus.reqres.request("get:subscription:for:domain", this.domainId);
        return this.subscriptionModel.fetch();
      };

      DomainEditController.prototype.showEditView = function(domainModel) {
        this.layout = this.getEditDomainLayout(domainModel);
        this.listenTo(this.layout, "show", (function(_this) {
          return function() {
            _this.layout.activeSubscriptionRegion.show(new Marionette.LoadingView);
            _this.layout.groupsRegion.show(new Marionette.LoadingView);
            App.execute("show:domain:groups", {
              region: _this.layout.groupsRegion,
              domain_id: _this.domainId
            });
            return msgbus.commands.execute("when:fetched", _this.subscriptionModel, function() {
              return _this.showActiveSubscription(_this.subscriptionModel);
            });
          };
        })(this));
        this.listenTo(this.layout, "edit:domain:clicked", this.editDomain);
        this.listenTo(this.layout, "delete:domain:clicked", this.deleteDomain);
        return this.show(this.layout, {
          loading: true
        });
      };

      DomainEditController.prototype.getEditDomainLayout = function(domainModel) {
        return new EditDomainView.DomainEditLayout({
          model: domainModel
        });
      };

      DomainEditController.prototype.getActiveSubscriptionView = function(subscriptionModel) {
        return new EditDomainView.ActiveSubscriptionView({
          model: subscriptionModel
        });
      };

      DomainEditController.prototype.showActiveSubscription = function(subscriptionModel) {
        var activeSubscriptionView;
        activeSubscriptionView = this.getActiveSubscriptionView(subscriptionModel);
        this.layout.activeSubscriptionRegion.show(activeSubscriptionView);
        return this.listenTo(activeSubscriptionView, "delete:pending:subscription", this.deleteSubscription);
      };

      DomainEditController.prototype.deleteSubscription = function(pendingSubscriptionId) {
        var options;
        options = {
          url: AJAXURL,
          method: "POST",
          data: {
            action: 'cancel-subscription',
            subscriptionId: pendingSubscriptionId
          }
        };
        return $.ajax(options).done((function(_this) {
          return function(response) {
            _this.subscriptionModel.unset('pending_subscription');
            return _this.showActiveSubscription(_this.subscriptionModel);
          };
        })(this));
      };

      DomainEditController.prototype.editDomain = function(domainData) {
        this.domainModel.set(domainData);
        return this.domainModel.save(null, {
          wait: true,
          success: this.domainUpdated
        });
      };

      DomainEditController.prototype.domainUpdated = function(userDomain) {
        var userDomainCollection;
        userDomainCollection = msgbus.reqres.request("get:current:user:domains");
        userDomainCollection.add(userDomain);
        return this.layout.triggerMethod("domain:updated");
      };

      DomainEditController.prototype.deleteDomain = function() {
        return this.domainModel.destroy({
          allData: false,
          wait: true,
          success: this.domainDeleted
        });
      };

      DomainEditController.prototype.domainDeleted = function() {
        var mainUrl, redirect_url;
        mainUrl = window.location.href.replace(Backbone.history.getFragment(), '');
        redirect_url = "" + mainUrl + "#domains";
        return window.location.href = redirect_url;
      };

      return DomainEditController;

    })(RegionController);
    return App.commands.setHandler("edit:user:domain", function(opts) {
      return new DomainEditController(opts);
    });
  });
});
