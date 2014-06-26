#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/add/domain-add-view'
         'msgbus' ], ( App, RegionController, DomainAddView, msgbus )->

    #start the app module
    App.module "UserDomainApp.Add", ( Add, App, BackBone, Marionette, $, _ )->

        # Controller class for adding a domain
        class DomainAddController extends RegionController

            initialize : ( opts )->

                #get user domain layout
                @view = @getView()

                #listen to the add domain button click event
                @listenTo @view, "add:domain:clicked", @addNewUserDomain
                @listenTo @view, "show:domain:list:clicked", ->
                    App.execute "list:user:domains", region : App.mainContentRegion

                @show @view,
                    loading : true

            getView : ->
                new DomainAddView

            addNewUserDomain : ( domaindata )=>
                userDomain = msgbus.reqres.request "create:domain:model", domaindata
                userDomain.save null,
                    wait : true
                    success : @userDomainSaved

            userDomainSaved : ( userDomain )=>
                userDomainCollection = msgbus.reqres.request "get:current:user:domains"
                userDomainCollection.add userDomain
                domainId = userDomain.get 'ID'
                @view.triggerMethod "user:domain:added", domainId


        #handler for showing the user domain page,options to be passed to controller are:
        # region :  App.mainContentRegion
        App.commands.setHandler "add:user:domains", ( opts ) ->
            new DomainAddController opts



