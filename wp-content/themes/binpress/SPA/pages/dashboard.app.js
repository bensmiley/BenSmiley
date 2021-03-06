define(['marionette', 'msgbus'], function(Marionette, msgbus) {
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
  App.rootRoute = "#domains";
  msgbus.reqres.setHandler("default:region", function() {
    return App.mainContentRegion;
  });
  msgbus.commands.setHandler("register:instance", function(instance, id) {
    return App.register(instance, id);
  });
  App.commands.setHandler("when:fetched", function(entities, callback) {
    var xhrs;
    xhrs = _.chain([entities]).flatten().pluck("_fetch").value();
    return $.when.apply($, xhrs).done(function() {
      return callback();
    });
  });
  msgbus.commands.setHandler("unregister:instance", function(instance, id) {
    return App.unregister(instance, id);
  });
  App.on("initialize:after", function(options) {
    App.startHistory();
    if (!App.getCurrentRoute()) {
      App.navigate(this.rootRoute, {
        trigger: true
      });
    }
    return App.vent.trigger('app:started');
  });
  return App;
});
