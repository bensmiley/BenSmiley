define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->
    class GroupModel extends Backbone.Model

        name : 'domain-group'

        idAttribute : 'ID'

        defaults : ->
            domain_id : 0

    # collection
    #    class GroupCollection extends Backbone.Collection
    #
    #        model: GroupModel
    #
    #        url: ->
    #            "#{AJAXURL}?action=fetch-user-domains"
    #
    #
    #    # create  a  collection
    #    userDomainCollection = new UserDomainCollection

    #PUBLIC API
    API =

#        getCurrentUserDomains : ->
#            userDomainCollection

        createGroupModel : ( data ) ->
            groupModel = new GroupModel data
            groupModel

    #Handlers
    #    msgbus.reqres.setHandler "get:current:user:domains", ->
    #        API.getCurrentUserDomains()

    msgbus.reqres.setHandler "create:domain:group:model", ( data ) ->
        API.createGroupModel data

    GroupModel