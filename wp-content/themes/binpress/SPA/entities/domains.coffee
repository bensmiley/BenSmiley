define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->

    class UserDomainModel extends Backbone.Model

        name : 'user-domain'

        defaults: ->
            user_id: CURRENTUSERDATA.ID

    # collection
    class UserDomainCollection extends Backbone.Collection

        model: UserDomainModel

        url: ->
            "#{AJAXURL}?action=fetch-user-domains"


    # create  a  collection
    userDomainCollection = new UserDomainCollection

    #PUBLIC API
    API =

        getCurrentUserDomains : ->
            userDomainCollection

        createCurrentUserDomainModel :(data) ->
            userDomainModel = new UserDomainModel data
            userDomainModel

    #Handlers
    msgbus.reqres.setHandler "get:current:user:domains", ->
        API.getCurrentUserDomains()

    msgbus.reqres.setHandler "create:current:user:domain:model",(data) ->
        API.createCurrentUserDomainModel data

    UserDomainModel