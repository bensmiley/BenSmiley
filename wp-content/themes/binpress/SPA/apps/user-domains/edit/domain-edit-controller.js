(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['app', 'regioncontroller', 'apps/user-domains/edit/domain-edit-view', 'msgbus', 'apps/user-domains/groups/add/add-group-controller', 'apps/user-domains/groups/list/list-group-controller'], function(App, RegionController, EditDomainView, msgbus) {
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
          return this.domainModel.fetch({
            success: this.showEditView
          });
        };

        DomainEditController.prototype.showEditView = function(domainModel) {
          this.layout = this.getEditDomainLayout(domainModel);
          this.listenTo(this.layout, "show", (function(_this) {
            return function() {
              App.execute("add:domain:groups", {
                region: _this.layout.addDomainGroupRegion,
                domain_id: _this.domainId
              });
              App.execute("list:domain:groups", {
                region: _this.layout.listDomainGroupRegion,
                domain_id: _this.domainId
              });
              _this.subscriptionModel = msgbus.reqres.request("get:subscription:for:domain", _this.domainId);
              console.log("how");
              return _this.subscriptionModel.fetch({
                success: _this.showActiveSubscription
              });
            };
          })(this));
          this.listenTo(this.layout, "edit:domain:clicked", this.editDomain);
          return this.show(this.layout, {
            loading: true,
            entities: this.subscriptionModel
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
          this.activeSubscriptionView = this.getActiveSubscriptionView(subscriptionModel);
          return this.layout.activeSubscriptionRegion.show(this.activeSubscriptionView);
        };

        DomainEditController.prototype.editDomain = function(domainData) {
          this.domainModel.set(domainData);
          return this.domainModel.save(null, {
            wait: true,
            success: this.domainUpdated
          });
        };

        DomainEditController.prototype.domainUpdated = function() {
          return this.layout.triggerMethod("domain:updated");
        };

        return DomainEditController;

      })(RegionController);
      return App.commands.setHandler("edit:user:domain", function(opts) {
        return new DomainEditController(opts);
      });
    });
  });

}).call(this);
