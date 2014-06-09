<<<<<<< HEAD
##
## The main dashboard App
##
define ['marionette'], (Marionette)->

    window.App = new Marionette.Application

    # Main app regions
    App.addRegions
        headerRegion : '#header-region'
        leftNavRegion : '#left-nav-region'
        mainContentRegion : '#main-content-region'
        breadcrumbRegion : '#breadcrumb-region'
        footerRegion : '#footer-region'
        dialogRegion : '#dialog-region'
        loginRegion : '#login-region'

    # The default route for app
    App.rootRoute = "dashboard"

    App.on 'start', ->
        console.log "Application Started..."

    # Reqres handler to return a default region. If a controller is not explicitly specified a
    # region it will trigger default region handler
    App.reqres.setHandler "default:region", ->
        App.mainContentRegion

    # App command to handle async request and action to be performed after that
    # entities are the the dependencies which trigger a fetch to server.
    App.commands.setHandler "when:fetched", (entities, callback) ->
        xhrs = _.chain([entities]).flatten().pluck("_fetch").value()
        $.when(xhrs...).done ->
            callback()

    # Registers a controller instance
    App.commands.setHandler "register:instance", (instance, id) ->
        App.register instance, id

    # Unregisters a controller instance
    App.commands.setHandler "unregister:instance", (instance, id) ->
        App.unregister instance, id

    App.on "initialize:after", (options) ->
        App.startHistory()
        #App.execute "show:headerapp", region:App.headerRegion
        #App.execute "show:leftnavapp", region:App.leftNavRegion
        App.navigate(@rootRoute, trigger : true) unless App.getCurrentRoute()

    App
=======
# set all plugins for this SPA here
define "plugins-loader", [ 'underscore', 'jquery', 'backbone', 'marionette', 'backbonesyphon', 'jqueryvalidate' ], ->

# set all plugin configurations for this SPA here
define "config-loader", [ 'configs/backbone.config', 'configs/marionette.config', 'configs/jquery.config' ], ->

define "apps-loader", [ 'apps/leftnav/leftnav-app'
                        'apps/header/header-app'
                        'apps/upload/upload-controller'
                        'apps/user-profile/user-profile-controller'
                        'apps/user-domains/show/user-domains-show-controller' ]

define "entitites-loader", [ 'entities/user' ]

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
>>>>>>> 6888129e2109d9ed9ad8b860bce95e11f998974a
