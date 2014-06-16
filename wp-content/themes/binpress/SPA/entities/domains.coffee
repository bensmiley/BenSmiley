define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->
    # model
    class UserDomainModel extends Backbone.Model

        name : 'user-domain'

        idAttribute : 'ID'

        defaults : ->
            user_id : CURRENTUSERDATA.ID

    # collection
    class UserDomainCollection extends Backbone.Collection

        model : UserDomainModel

        url : ->
            "#{AJAXURL}?action=fetch-user-domains"


    # create  a  collection
    userDomainCollection = new UserDomainCollection

    #PUBLIC API
    API =

        getCurrentUserDomains : ->
            userDomainCollection

        createCurrentUserDomainModel : ( data ) ->
            userDomainModel = new UserDomainModel data
            userDomainModel

        getDomainById : ( domainId ) ->
            domainModel = new UserDomainModel 'ID' : parseInt domainId
            domainModel

    #Handlers
    msgbus.reqres.setHandler "get:current:user:domains", ->
        API.getCurrentUserDomains()

    msgbus.reqres.setHandler "get:domain:model:by:id",( domainId ) ->
        API.getDomainById domainId

    msgbus.reqres.setHandler "create:current:user:domain:model", ( data ) ->
        API.createCurrentUserDomainModel data

    UserDomainModel