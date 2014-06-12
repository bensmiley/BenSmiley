#TODO: add proper comments
#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/add/domain-add-view'
         'msgbus' ], ( App, RegionController, DomainAddView, msgbus )->

    #start the app module
    App.module "UserDomainApp.Add", ( Add, App, BackBone, Marionette, $, _ )->

        # Controller class for showing user domain list
        class DomainAddController extends RegionController

            initialize : ( opts )->

                #get user domain layout
                @layout = @getLayout()

                #listen to the add domain button click event
                @listenTo @layout, "add:user:domain:clicked", @addNewUserDomain

                @show @layout

            getLayout : ->
                new DomainAddView

            addNewUserDomain : ( domaindata )=>
                userDomain = msgbus.reqres.request "create:current:user:domain:model", domaindata
                userDomain.save null,
                    wait : true
                    success : @userDomainSaved

            userDomainSaved : ( userDomain )=>
                userDomainCollection = msgbus.reqres.request "get:current:user:domains"
                userDomainCollection.add userDomain
                @layout.triggerMethod "user:domain:added"


        #handler for showing the user domain page,options to be passed to controller are:
        # region :  App.mainContentRegion
        App.commands.setHandler "add:user:domains", ( opts ) ->
            new DomainAddController opts



