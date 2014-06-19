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

    # create  a  collection
    groupCollection = new GroupCollection

    #PUBLIC API
    API =

        createGroupModel : ( data ) ->
            groupModel = new GroupModel data
            groupModel

        getGroupsByDomainId : ->
            groupCollection


    #Handlers
    msgbus.reqres.setHandler "create:domain:group:model", ( data ) ->
        API.createGroupModel data

    msgbus.reqres.setHandler "get:groups:for:domains", ->
        API.getGroupsByDomainId()

    GroupModel : GroupModel
    GroupCollection : GroupCollection