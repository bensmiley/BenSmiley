#TODO: add proper comments
#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/add/user-domain-add-view'
         'msgbus'], ( App, AppController, UserDomainAddView , msgbus )->

    #start the app module
    App.module "UserDomainAddApp", ( UserDomainAddApp, App, BackBone, Marionette, $, _ )->

        # Controller class for showing user domain list
        class UserDomainAddController extends AppController

            initialize : ( opts )->

                #get user domain layout
                @layout = @getLayout()

                #listen to the add domain button click event
                @listenTo @layout ,"add:user:domain:clicked",@addNewUserDomain

                @show @layout

            getLayout : ->
                new UserDomainAddView

            addNewUserDomain :(domaindata)=>
                userDomain= msgbus.reqres.request "create:current:user:domain:model", domaindata
                userDomain.save null,
                    wait: true
                    success: @userDomainSaved

            userDomainSaved :(userDomain)=>
                userDomainCollection = msgbus.reqres.request "get:current:user:domains"
                userDomainCollection.add userDomain
                @layout.triggerMethod "user:domain:added"
                console.log userDomainCollection
                console.log userDomain




        #handler for showing the user domain page : triggered from left nav region
        App.commands.setHandler "add:user:domain", ( opts ) ->
            new UserDomainAddController opts



