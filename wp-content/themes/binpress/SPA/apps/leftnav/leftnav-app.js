define(['app', 'apps/leftnav/show/leftnav-controller'], function(App) {
  return App.module('LeftNavApp', function(LeftNavApp, App, Backbone, Marionette, $, _) {
    var API, leftnavController;
    leftnavController = null;
    API = {
      show: function() {
        return leftnavController = new LeftNavApp.Show.Controller({
          region: App.leftNavRegion
        });
      }
    };
    return LeftNavApp.on('start', function() {
      return API.show();
    });
  });
});
