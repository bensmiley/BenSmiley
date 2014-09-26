define(['backbone', 'marionette', 'jquery', 'underscore'], function(Backbone, Marionette, $, _) {
  var msgbus;
  msgbus = Backbone.Wreqr.radio.channel("global");
  msgbus.commands.setHandler("when:fetched", function(entities, callback) {
    var xhrs;
    xhrs = _.chain([entities]).flatten().pluck("_read").value();
    return $.when.apply($, xhrs).done(function() {
      return callback();
    });
  });
  msgbus.commands.setHandler("when:created", function(entities, callback) {
    var xhrs;
    xhrs = _.chain([entities]).flatten().pluck("_create").value();
    return $.when.apply($, xhrs).done(function() {
      return callback();
    });
  });
  msgbus.commands.setHandler("when:deleted", function(entities, callback) {
    var xhrs;
    xhrs = _.chain([entities]).flatten().pluck("_delete").value();
    return $.when.apply($, xhrs).done(function() {
      return callback();
    });
  });
  msgbus.commands.setHandler("when:updated", function(entities, callback) {
    var xhrs;
    xhrs = _.chain([entities]).flatten().pluck("_update").value();
    return $.when.apply($, xhrs).done(function() {
      return callback();
    });
  });
  return msgbus;
});
