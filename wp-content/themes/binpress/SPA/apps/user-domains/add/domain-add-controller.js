var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'apps/user-domains/add/domain-add-view', 'msgbus'], function(App, RegionController, DomainAddView, msgbus) {
  return App.module("UserDomainApp.Add", function(Add, App, BackBone, Marionette, $, _) {
    var DomainAddController;
    DomainAddController = (function(_super) {
      __extends(DomainAddController, _super);

      function DomainAddController() {
        this.userDomainSaved = __bind(this.userDomainSaved, this);
        this.addNewUserDomain = __bind(this.addNewUserDomain, this);
        return DomainAddController.__super__.constructor.apply(this, arguments);
      }

      DomainAddController.prototype.initialize = function(opts) {
        this.view = this.getView();
        this.listenTo(this.view, "add:domain:clicked", this.addNewUserDomain);
        return this.show(this.view, {
          loading: true
        });
      };

      DomainAddController.prototype.getView = function() {
        return new DomainAddView;
      };

      DomainAddController.prototype.addNewUserDomain = function(domaindata) {
        var userDomain;
        userDomain = msgbus.reqres.request("create:domain:model", domaindata);
        return userDomain.save(null, {
          wait: true,
          success: this.userDomainSaved
        });
      };

      DomainAddController.prototype.userDomainSaved = function(userDomain, response) {
        var domainId, userDomainCollection;
        if (response.code === "ERROR") {
          return this.view.triggerMethod("user:domain:add:error", response.msg);
        } else if (response.code === "OK") {
          userDomainCollection = msgbus.reqres.request("get:current:user:domains");
          userDomainCollection.add(userDomain);
          domainId = userDomain.get('ID');
          return this.view.triggerMethod("user:domain:added", domainId);
        }
      };

      return DomainAddController;

    })(RegionController);
    return App.commands.setHandler("add:user:domains", function(opts) {
      return new DomainAddController(opts);
    });
  });
});
