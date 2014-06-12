#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/user-domains/edit/domain-edit-view'
         'msgbus'
         'apps/user-domains/groups/add/add-group-controller' ], ( App, RegionController, DomainEditView, msgbus )->

    #start the app module
    App.module "UserDomainApp.Edit", ( Edit, App, BackBone, Marionette, $, _ )->

        # Controller class for add/edit of user domain
        class DomainEditController extends RegionController

            initialize : ( opts )->
                domainModel = msgbus.reqres.request "get:domain:model:by:id", opts.domainId
                domainModel.fetch().done @showEditView

            showEditView:( domainModel )=>
                console.log domainModel

                @layout = @geEditDomainLayout domainModel

                @show @layout
#                #if the domain model is not passed: add domain view
#                if _.isUndefined( opts.model )
#                    #get add domain layout
#                    @layout = @getAddDomainLayout()
#                else
#                    @model = opts.model
#                    #get edit domain layout
#                    @layout = @getEditDomainLayout @model
#                    @listenTo @layout, "show", =>
#                        App.execute "add:domain:groups",
#                            region : @layout.addDomainGroupRegion
#                            domain_id : @model.get 'ID'
#
#                #listen to the add domain button click event
#                @listenTo @layout, "add:edit:user:domain:clicked", @addEditUserDomain
#
#                #listen to the show domain list click event
#                @listenTo @layout, "show:domain:list:clicked", ->
#                    App.execute "show:user:domains", region : App.mainContentRegion
#
#



#
#
#            getAddDomainLayout : ->
#                new UserDomainAddView
#
            geEditDomainLayout : ( domainModel ) ->
                new DomainEditView
                    model : domainModel
#
#            addEditUserDomain : ( domaindata )=>
#                #add domain
#                if _.isUndefined( @model )
#                    userDomain = msgbus.reqres.request "create:current:user:domain:model", domaindata
#                    userDomain.save null,
#                        wait : true
#                        success : @userDomainAddUpdate
#                    #edit domain
#                else
#                    @model.set domaindata
#                    @model.save null,
#                        wait : true
#                        success : @userDomainAddUpdate
#
#
#            userDomainAddUpdate : ( userDomain )=>
#                if not _.isUndefined( @model )
#                    userDomainCollection = msgbus.reqres.request "get:current:user:domains"
#                    userDomainCollection.add userDomain
#                @layout.triggerMethod "user:domain:add:update"


        #handler for edit domain page,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainId : parseInt id
        App.commands.setHandler "edit:user:domain", ( opts ) ->
            new DomainEditController opts



