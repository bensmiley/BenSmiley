(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['marionette', 'text!apps/plans/templates/changePlanLayout.html', 'braintree', 'card'], function(Marionette, changePlanTpl, BrainTree, card) {
    var ChangePlanView;
    ChangePlanView = (function(_super) {
      __extends(ChangePlanView, _super);

      function ChangePlanView() {
        return ChangePlanView.__super__.constructor.apply(this, arguments);
      }

      ChangePlanView.prototype.template = changePlanTpl;

      ChangePlanView.prototype.onShow = function() {
        return console.log(this.model);
      };

      return ChangePlanView;

    })(Marionette.CompositeView);
    return ChangePlanView;
  });

}).call(this);
