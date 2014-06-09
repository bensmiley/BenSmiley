// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'text!apps/user-domains/templates/ListUserDomain.html'], function(App, listUserDomainTpl) {
  return App.module('UserDomainAppView', function(View, App) {
    var DomainItemView, EmptyView;
    View.UserDomainView = (function(_super) {
      __extends(UserDomainView, _super);

      function UserDomainView() {
        return UserDomainView.__super__.constructor.apply(this, arguments);
      }

      UserDomainView.prototype.className = 'user-domain-container';

      UserDomainView.prototype.template = '<!-- TABS --> <ul class="nav nav-tabs" id="tab-01"> <li class="active"><a href="#domain-details">Domain Details</a></li> <li><a href="#tab1FollowUs">Domain Plan</a></li> <li><a href="#tab1Inspire">Statistics</a></li> </ul> <div class="tab-content"> <!-- Show user domain and add new user domain region --> <div class="tab-pane active" id="domain-details"></div> <hr> </div>';

      UserDomainView.prototype.regions = {
        domainListRegion: '#domain-details'
      };

      UserDomainView.prototype.events = {
        'click #btn-add-domain': function() {
          return this.trigger("add:user:domain:clicked");
        }
      };

      return UserDomainView;

    })(Marionette.Layout);
    DomainItemView = (function(_super) {
      __extends(DomainItemView, _super);

      function DomainItemView() {
        return DomainItemView.__super__.constructor.apply(this, arguments);
      }

      DomainItemView.prototype.tagName = 'tr';

      DomainItemView.prototype.template = '<td>Minyawns</td> <td>Silver</td> <td>09/21/2014</td> <td class="center"> <span class="glyphicon glyphicon-pencil"></span> <span class="glyphicon glyphicon-trash"></span> </td>';

      return DomainItemView;

    })(Marionette.ItemView);
    EmptyView = (function(_super) {
      __extends(EmptyView, _super);

      function EmptyView() {
        return EmptyView.__super__.constructor.apply(this, arguments);
      }

      EmptyView.prototype.tagName = 'tr';

      EmptyView.prototype.template = '<td>You have not added any domains yet</td>';

      return EmptyView;

    })(Marionette.ItemView);
    return View.DomainListView = (function(_super) {
      __extends(DomainListView, _super);

      function DomainListView() {
        return DomainListView.__super__.constructor.apply(this, arguments);
      }

      DomainListView.prototype.template = listUserDomainTpl;

      DomainListView.prototype.itemView = DomainItemView;

      DomainListView.prototype.emptyView = EmptyView;

      DomainListView.prototype.itemViewContainer = 'tbody';

      return DomainListView;

    })(Marionette.CompositeView);
  });
});
