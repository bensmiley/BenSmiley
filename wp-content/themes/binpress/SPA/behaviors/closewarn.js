var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette'], function(Marionette) {
  var CloseWarn;
  CloseWarn = (function(_super) {
    __extends(CloseWarn, _super);

    function CloseWarn() {
      return CloseWarn.__super__.constructor.apply(this, arguments);
    }

    CloseWarn.prototype.defaults = {
      message: "you are closing!"
    };

    CloseWarn.prototype.events = {
      "click @ui.close": "warnBeforeClose"
    };

    CloseWarn.prototype.warnBeforeClose = function() {
      alert(this.options.message);
      return this.view.close();
    };

    return CloseWarn;

  })(Marionette.Behavior);
  return CloseWarn;
});
