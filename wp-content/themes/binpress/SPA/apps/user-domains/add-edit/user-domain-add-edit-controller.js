// Generated by CoffeeScript 1.7.1
var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'apps/user-domains/add-edit/user-domain-add-edit-view', 'msgbus'], function(App, AppController, UserDomainAddView, msgbus) {
  return App.module("UserDomainAddEditApp", function(UserDomainAddEditApp, App, BackBone, Marionette, $, _) {
    var UserDomainAddEditController;
    UserDomainAddEditController = (function(_super) {
      __extends(UserDomainAddEditController, _super);

      function UserDomainAddEditController() {
        this.userDomainAddUpdate = __bind(this.userDomainAddUpdate, this);
        this.addEditUserDomain = __bind(this.addEditUserDomain, this);
        return UserDomainAddEditController.__super__.constructor.apply(this, arguments);
      }

      UserDomainAddEditController.prototype.initialize = function(opts) {
        if (_.isUndefined(opts.model)) {
          this.layout = this.getAddDomainLayout();
        } else {
          this.model = opts.model;
          this.layout = this.getEditDomainLayout(this.model);
        }
        this.listenTo(this.layout, "add:edit:user:domain:clicked", this.addEditUserDomain);
        this.listenTo(this.layout, "show:domain:list:clicked", function() {
          return App.execute("show:user:domains", {
            region: App.mainContentRegion
          });
        });
        return this.show(this.layout);
      };

      UserDomainAddEditController.prototype.getAddDomainLayout = function() {
        return new UserDomainAddView;
      };

      UserDomainAddEditController.prototype.getEditDomainLayout = function(domainmodel) {
        return new UserDomainAddView({
          model: domainmodel
        });
      };

      UserDomainAddEditController.prototype.addEditUserDomain = function(domaindata) {
        var userDomain;
        if (_.isUndefined(this.model)) {
          userDomain = msgbus.reqres.request("create:current:user:domain:model", domaindata);
          return userDomain.save(null, {
            wait: true,
            success: this.userDomainAddUpdate
          });
        } else {
          this.model.set(domaindata);
          return this.model.save(null, {
            wait: true,
            success: this.userDomainAddUpdate
          });
        }
      };

      UserDomainAddEditController.prototype.userDomainAddUpdate = function(userDomain) {
        var userDomainCollection;
        if (!_.isUndefined(this.model)) {
          userDomainCollection = msgbus.reqres.request("get:current:user:domains");
          userDomainCollection.add(userDomain);
        }
        return this.layout.triggerMethod("user:domain:add:update");
      };

      return UserDomainAddEditController;

    })(AppController);
    return App.commands.setHandler("add:edit:user:domain", function(opts) {
      return new UserDomainAddEditController(opts);
    });
  });
});