// Generated by CoffeeScript 1.7.1
define(['marionette'], function(Marionette) {
  window.App = new Marionette.Application;
  App.addRegions({
    headerRegion: '#header-region',
    leftNavRegion: '#left-nav-region',
    mainContentRegion: '#main-content-region',
    breadcrumbRegion: '#breadcrumb-region',
    footerRegion: '#footer-region',
    dialogRegion: '#dialog-region',
    loginRegion: '#login-region'
  });
  App.rootRoute = "dashboard";
  App.on('start', function() {
    return console.log("Application Started....");
  });
  App.reqres.setHandler("default:region", function() {
    return App.mainContentRegion;
  });
  App.commands.setHandler("when:fetched", function(entities, callback) {
    var xhrs;
    xhrs = _.chain([entities]).flatten().pluck("_fetch").value();
    return $.when.apply($, xhrs).done(function() {
      return callback();
    });
  });
  App.commands.setHandler("register:instance", function(instance, id) {
    return App.register(instance, id);
  });
  App.commands.setHandler("unregister:instance", function(instance, id) {
    return App.unregister(instance, id);
  });
  App.on("initialize:after", function(options) {
    App.startHistory();
    if (!App.getCurrentRoute()) {
      return App.navigate(this.rootRoute, {
        trigger: true
      });
    }
  });
  return App;
});