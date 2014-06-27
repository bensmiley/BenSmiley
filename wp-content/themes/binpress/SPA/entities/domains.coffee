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

    #PUBLIC API
    API =

        getCurrentUserDomains : ->
            userDomainCollection = new UserDomainCollection
            userDomainCollection.fetch()
            userDomainCollection

        createCurrentUserDomainModel : ( data ) ->
            userDomainModel = new UserDomainModel data
            userDomainModel

        getDomainById : ( domainId ) ->
            domainModel = new UserDomainModel 'ID' : parseInt domainId
            domainModel.fetch()
            domainModel

    #Handlers
    msgbus.reqres.setHandler "get:current:user:domains", ->
        API.getCurrentUserDomains()

    msgbus.reqres.setHandler "get:domain:model:by:id", ( domainId ) ->
        API.getDomainById domainId

    msgbus.reqres.setHandler "create:domain:model", ( data ) ->
        API.createCurrentUserDomainModel data

    UserDomainModel : UserDomainModel
    UserDomainCollection : UserDomainCollection