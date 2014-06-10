#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/add-edit/user-domain-add-edit-view'
         'msgbus'
         'apps/user-domains/groups/add/add-group-controller'], ( App, AppController, UserDomainAddView , msgbus )->

    #start the app module
    App.module "UserDomainAddEditApp", ( UserDomainAddEditApp, App, BackBone, Marionette, $, _ )->

        # Controller class for add/edit of user domain
        class UserDomainAddEditController extends AppController

            initialize : ( opts )->

                #if the domain model is not passed: add domain view
                if _.isUndefined(opts.model)
                    #get add domain layout
                    @layout = @getAddDomainLayout()
                else
                    @model = opts.model
                    #get edit domain layout
                    @layout = @getEditDomainLayout @model
                    @listenTo @layout,"show",=>
                        App.execute "add:domain:groups" ,
                            region : @layout.addDomainGroupRegion
                            domain_id : @model.get 'ID'

                #listen to the add domain button click event
                @listenTo @layout ,"add:edit:user:domain:clicked",@addEditUserDomain

                #listen to the show domain list click event
                @listenTo @layout ,"show:domain:list:clicked",->
                    App.execute "show:user:domains", region : App.mainContentRegion

                @show @layout

            getAddDomainLayout : ->
                new UserDomainAddView

            getEditDomainLayout :(domainmodel) ->
                new UserDomainAddView
                    model : domainmodel

            addEditUserDomain :(domaindata)=>
                #add domain
                if _.isUndefined(@model)
                    userDomain= msgbus.reqres.request "create:current:user:domain:model", domaindata
                    userDomain.save null,
                        wait: true
                        success: @userDomainAddUpdate
                #edit domain
                else
                    @model.set domaindata
                    @model.save null,
                        wait: true
                        success: @userDomainAddUpdate


            userDomainAddUpdate :(userDomain)=>
                if not _.isUndefined(@model)
                    userDomainCollection = msgbus.reqres.request "get:current:user:domains"
                    userDomainCollection.add userDomain
                @layout.triggerMethod "user:domain:add:update"


        #handler for showing the add-edit domain page
        App.commands.setHandler "add:edit:user:domain", ( opts ) ->
            new UserDomainAddEditController opts



