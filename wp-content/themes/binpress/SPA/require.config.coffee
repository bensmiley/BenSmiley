# load all the scripts required for SPA
requirejs.config
    urlArgs : "?ver=#{Math.random()}"
    baseUrl : '../wp-content/themes/binpress/SPA/'
    paths :
        jquery : 'bower_components/jquery/dist/jquery'
        backbone : 'bower_components/backbone/backbone'
        underscore : 'bower_components/underscore/underscore'
        marionette : 'bower_components/marionette/lib/backbone.marionette'
        mustache : 'bower_components/mustache/mustache'
        text : 'bower_components/requirejs-text/text'
        backbonesyphon : 'bower_components/backbone.syphon/lib/backbone.syphon'
        plupload : 'bower_components/plupload/js/plupload.full.min'
        jqueryvalidate : 'bower_components/jquery.validation/dist/jquery.validate'
        regioncontroller : 'controllers/region-controller'
        bootstrap : 'bower_components/bootstrap/dist/js/bootstrap.min'
        braintree : 'https://js.braintreegateway.com/v1/braintree'
    shim :
        underscore :
            exports : '_'
        jquery : ['underscore']
        backbone :
            deps : ['jquery', 'underscore']
            exports : 'Backbone'
        braintree :
            deps : ['jquery']
            exports : 'Braintree'
        marionette :
            deps : ['backbone']
            exports : 'Marionette'
        backbonesyphon : ['backbone']
        jqueryvalidate : ['jquery']
        bootstrap : [ 'jquery' ]
        plupload :
            deps : ['jquery']
            exports : 'plupload'