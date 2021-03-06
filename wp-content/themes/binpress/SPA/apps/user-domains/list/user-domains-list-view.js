var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'text!apps/user-domains/templates/listUserDomain.html'], function(Marionette, listUserDomainTpl) {
  var DomainItemView, DomainListView, EmptyView;
  DomainItemView = (function(_super) {
    __extends(DomainItemView, _super);

    function DomainItemView() {
      return DomainItemView.__super__.constructor.apply(this, arguments);
    }

    DomainItemView.prototype.tagName = 'tr';

    DomainItemView.prototype.template = '<td>{{post_title}}</td> <td>{{plan_name}}</td> <td>{{post_date}}</td>';

    DomainItemView.prototype.events = {
      'click': function() {
        var domainId, mainUrl, redirect_url;
        domainId = this.model.get('ID');
        mainUrl = window.location.href.replace(Backbone.history.getFragment(), '');
        redirect_url = "" + mainUrl + "domains/edit/" + domainId;
        return window.location.href = redirect_url;
      }
    };

    return DomainItemView;

  })(Marionette.ItemView);
  EmptyView = (function(_super) {
    __extends(EmptyView, _super);

    function EmptyView() {
      return EmptyView.__super__.constructor.apply(this, arguments);
    }

    EmptyView.prototype.tagName = 'tr';

    EmptyView.prototype.template = '<td colspan="4" align="center" class="nocol">You have not added any domains yet</td>';

    return EmptyView;

  })(Marionette.ItemView);
  DomainListView = (function(_super) {
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
  return {
    DomainListView: DomainListView
  };
});
