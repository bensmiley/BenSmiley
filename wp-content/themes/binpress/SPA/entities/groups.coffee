define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->
    #model
    class GroupModel extends Backbone.Model

        name : 'domain-group'

        idAttribute : 'ID'

        defaults : ->
            domain_id : 0

    # collection
    class GroupCollection extends Backbone.Collection

        model : GroupModel

        url : ->
            "#{AJAXURL}?action=fetch-groups"

    #PUBLIC API
    API =

        createGroupModel : ( data ) ->
            groupModel = new GroupModel data
            groupModel

        getGroupsByDomainId : ( domainId )->
            groupCollection = new GroupCollection
            groupCollection.fetch
                data :
                    domain_id : domainId
            groupCollection


    #Handlers
    msgbus.reqres.setHandler "create:domain:group:model", ( data ) ->
        API.createGroupModel data

    msgbus.reqres.setHandler "get:groups:for:domains", ( domainId ) ->
        API.getGroupsByDomainId domainId

    GroupModel : GroupModel
    GroupCollection : GroupCollection