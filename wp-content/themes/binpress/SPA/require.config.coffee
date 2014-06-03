# load all the scripts required for SPA
requirejs.config
    urlArgs : "?ver=#{Math.random()}"
    baseUrl : '../wp-content/themes/binpress/SPA/'
    paths :
        jquery : 'bower_components/jquery/dist/jquery'
        backbone : 'bower_components/backbone/backbone'
        underscore : 'bower_components/underscore/underscore'
        marionette : 'bower_components/marionette/lib/backbone.marionette.min'
        mustache : 'bower_components/mustache/mustache'
        text : 'bower_components/requirejs-text/text'
        backbonesyphon : 'bower_components/backbone.syphon/lib/backbone.syphon'
        configloader : 'configs/dashboard-spa-config-loader'
        appsloader : 'apps/dashboard-spa-apps-loader'
        'base-controller' : 'controllers/base-controller'
        entitiesloader : 'entities/dashboard-spa-entities-loader'
        app : 'dashboard-spa'


    shim :
        underscore :
            exports : '_'
        jquery : ['underscore']
        backbone :
            deps : ['jquery', 'underscore']
            exports : 'Backbone'
        marionette :
            deps : ['backbone']
            exports : 'Marionette'
        backbonesyphon: ['backbone']
        app : ['configloader']

## Start with application
require [   'configloader'
            'app',
            'base-controller',
            'appsloader',
            'entitiesloader'], (configs, App)->
    App.start()