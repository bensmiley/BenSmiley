#include the files for the app
define [ 'app'
         'msgbus'
         'apps/user-domains/list/user-domains-list-controller'
         'apps/user-domains/edit/domain-edit-controller'
         'apps/user-domains/add/domain-add-controller'], ( App, msgbus )->

    #start the app module
    App.module 'UserDomainApp', ( UserDomainApp, App, Backbone, Marionette, $, _ )->

        # define the route for the app
        class UserDomainAppRouter extends Marionette.AppRouter

            appRoutes :
                'domains' : 'list'
                'domains/add' : 'add'
                'domains/edit/:id' : 'edit'

        #public API
        API =
            list : ->
                App.execute "list:user:domains", region : App.mainContentRegion

            add : ->
                App.execute "add:user:domains", region : App.mainContentRegion

            edit : ( id )->
                App.execute "edit:user:domain",
                        region : App.mainContentRegion
                        domainId : id

        #on start of the app call the function based on the routes
        UserDomainApp.on 'start' : ->
            new UserDomainAppRouter
                controller : API

