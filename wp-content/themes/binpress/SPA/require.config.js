// Generated by CoffeeScript 1.7.1
requirejs.config({
  urlArgs: "?ver=" + (Math.random()),
  baseUrl: '../wp-content/themes/binpress/SPA/',
  paths: {
    jquery: 'bower_components/jquery/dist/jquery',
    backbone: 'bower_components/backbone/backbone',
    underscore: 'bower_components/underscore/underscore',
    marionette: 'bower_components/marionette/lib/backbone.marionette.min',
    mustache: 'bower_components/mustache/mustache',
    text: 'bower_components/requirejs-text/text',
    backbonesyphon: 'bower_components/backbone.syphon/lib/backbone.syphon',
    plupload: 'bower_components/plupload/js/plupload.full.min',
    'jquery-validate': 'bower_components/jquery.validation/dist/jquery.validate',
    configloader: 'configs/dashboard-spa-config-loader',
    appsloader: 'apps/dashboard-spa-apps-loader',
    'region-controller': 'controllers/region-controller',
    entitiesloader: 'entities/dashboard-spa-entities-loader',
    app: 'dashboard-spa'
  },
  shim: {
    underscore: {
      exports: '_'
    },
    jquery: ['underscore'],
    backbone: {
      deps: ['jquery', 'underscore'],
      exports: 'Backbone'
    },
    marionette: {
      deps: ['backbone'],
      exports: 'Marionette'
    },
    backbonesyphon: ['backbone'],
    'jquery-validate': ['jquery'],
    plupload: {
      deps: ['jquery'],
      exports: 'plupload'
    },
    app: ['configloader']
  }
});

require(['configloader', 'app', 'region-controller', 'appsloader', 'entitiesloader'], function(configs, App) {
  return App.start();
});