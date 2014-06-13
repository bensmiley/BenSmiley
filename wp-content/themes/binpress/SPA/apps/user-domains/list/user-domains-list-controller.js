// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'msgbus', 'apps/user-domains/list/user-domains-list-view'], function(App, RegionController, msgbus, View) {
  return App.module("UserDomainApp.List", function(List, App, BackBone, Marionette, $, _) {
    var UserDomainListController;
    UserDomainListController = (function(_super) {
      __extends(UserDomainListController, _super);

      function UserDomainListController() {
        return UserDomainListController.__super__.constructor.apply(this, arguments);
      }

      UserDomainListController.prototype.initialize = function(opts) {
        this.layout = this.getLayout();
        this.listenTo(this.layout, "show", (function(_this) {
          return function() {
            _this.userDomainsCollection = msgbus.reqres.request("get:current:user:domains");
            _this.userDomainsCollection.fetch();
            _this.domainListView = _this.getDomainListView(_this.userDomainsCollection);
            _this.layout.domainViewRegion.show(_this.domainListView);
            return _this.listenTo(_this.domainListView, "itemview:delete:domain:clicked", _this.deleteDomainClick);
          };
        })(this));
        return this.show(this.layout);
      };

      UserDomainListController.prototype.getLayout = function() {
        return new View.UserDomainView;
      };

      UserDomainListController.prototype.getDomainListView = function(userDomainsCollection) {
        return new View.DomainListView({
          collection: userDomainsCollection
        });
      };

      UserDomainListController.prototype.deleteDomainClick = function(iv, model) {
        return model.destroy({
          allData: false,
          wait: true
        });
      };

      return UserDomainListController;

    })(RegionController);
    return App.commands.setHandler("list:user:domains", function(opts) {
      return new UserDomainListController(opts);
    });
  });
});