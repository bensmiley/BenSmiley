requirejs.config({
  urlArgs: "?ver=" + (Math.random()),
  baseUrl: 'http://localhost/bensmiley/wp-content/themes/binpress/SPA/',
  paths: {
    jquery: 'bower_components/jquery/dist/jquery',
    backbone: 'bower_components/backbone/backbone',
    underscore: 'bower_components/underscore/underscore',
    marionette: 'bower_components/marionette/lib/core/amd/backbone.marionette',
    mustache: 'bower_components/mustache/mustache',
    text: 'bower_components/requirejs-text/text',
    backbonesyphon: 'bower_components/backbone.syphon/lib/amd/backbone.syphon',
    'backbone.wreqr': 'bower_components/backbone.wreqr/lib/backbone.wreqr',
    'backbone.babysitter': 'bower_components/backbone.babysitter/lib/backbone.babysitter',
    plupload: 'bower_components/plupload/js/plupload.full.min',
    jqueryvalidate: 'bower_components/jquery.validation/dist/jquery.validate',
    additionalmethods: 'bower_components/jquery.validation/dist/additional-methods',
    regioncontroller: 'controllers/region-controller',
    bootstrap: 'bower_components/bootstrap/dist/js/bootstrap.min',
    braintree: 'bower_components/braintree/braintree',
    spin: 'bower_components/spin.js/spin',
    jqueryspin: 'bower_components/spin.js/jquery.spin',
    card: 'bower_components/card/lib/js/card'
  },
  shim: {
    jquery: ['underscore'],
    backbone: ['jquery', 'underscore'],
    braintree: {
      deps: ['jquery'],
      exports: 'Braintree'
    },
    marionette: ['backbone'],
    backbonesyphon: ['backbone'],
    jqueryvalidate: ['jquery'],
    additionalmethods: ['jquery', 'jqueryvalidate'],
    bootstrap: ['jquery'],
    card: ['jquery'],
    jqueryspin: ['spin'],
    plupload: {
      deps: ['jquery'],
      exports: 'plupload'
    }
  }
});
