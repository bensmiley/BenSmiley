# set all plugins for this SPA here
define "plugins-loader", [ 'underscore'
                           'jquery'
                           'bootstrap'
                           'backbone'
                           'marionette'
                           'backbonesyphon'
                           'jqueryvalidate' ], ->

# set all plugin configurations for this SPA here
define "config-loader", [ 'configs/backbone.config', 'configs/marionette.config', 'configs/jquery.config' ], ->

define "apps-loader", [ 'apps/leftnav/leftnav-app'
                        'apps/header/header-app'
                        'apps/upload/upload-controller'
                        'apps/user-profile/user-profile-app'
                        'apps/user-domains/user-domains-app'
                        'apps/plans/plans-app'
                        'apps/payment/payment-app']

define "entitites-loader", [ 'entities/user'
                             'entities/domains'
                             'entities/groups'
                             'entities/plans']

# define 'app'
define "app", [ 'pages/dashboard.app' ], ( App ) ->
    App

# All Done, Load all in browser and start the App
require [ 'plugins-loader'
          'config-loader'
          'app'
          'entitites-loader'
          'apps-loader' ], ( p, c, App ) ->
    App.start()