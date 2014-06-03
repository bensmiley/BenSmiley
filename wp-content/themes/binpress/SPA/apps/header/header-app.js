// Generated by CoffeeScript 1.7.1
define(['app', 'apps/header/show/header-controller'], function(App) {
  return App.module('HeaderApp', function(HeaderApp, App, Backbone, Marionette, $, _) {
    var API, headerController;
    headerController = null;
    API = {
      show: function() {
        return headerController = new HeaderApp.Show.Controller({
          region: App.headerRegion
        });
      }
    };
    return HeaderApp.on('start', function() {
      return API.show();
    });
  });
});

//# sourceMappingURL=header-app.map
