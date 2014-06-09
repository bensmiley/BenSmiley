// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['app', 'regioncontroller', 'apps/upload/upload-view'], function(App, AppController, View) {
  return App.module('UploadApp', function(UploadApp, App, Backbone, Marionette, $, _) {
    UploadApp.Controller = (function(_super) {
      __extends(Controller, _super);

      function Controller() {
        return Controller.__super__.constructor.apply(this, arguments);
      }

      Controller.prototype.initialize = function(opts) {
        var view;
        this.usermodel = opts.model;
        view = this._getView(this.usermodel);
        return this.show(view);
      };

      Controller.prototype._getView = function() {
        return new View.UploadView({
          model: usermodel
        });
      };

      return Controller;

    })(AppController);
    return App.commands.setHandler('start:upload:app', function(options) {
      return new UploadApp.Controller(options);
    });
  });
});
