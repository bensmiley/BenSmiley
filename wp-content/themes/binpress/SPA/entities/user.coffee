define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->

    #define user model
    class UserModel extends Backbone.Model

        name : 'user'

        idAttribute : 'ID'

    #        parse :(response) ->
    #            response.ID = parseInt response.ID
    #            response

    # TODO : handle with better logic
    # This is current user model (Logged in user model)
    CURRENTUSERDATA['ID'] = parseInt CURRENTUSERDATA['ID']
    currentUser = new UserModel
    currentUser.set CURRENTUSERDATA


    #PUBLIC API
    API =
        getCurrentUser : ->
            currentUser

        getCurrentUserId : ->
            currentUser.get 'ID'

        getUserById : ( userId )->
            userModel = {}
            if currentUser.get( 'ID' ) is userId
                userModel = currentUser
            else
                userModel = new UserModel ID : userId
                userModel.fetch()

            userModel

    #Handlers
    msgbus.reqres.setHandler "get:current:user:model", ->
        API.getCurrentUser()

    msgbus.reqres.setHandler "get:user:model", ( userId ) ->
        API.getUserById userId

    msgbus.reqres.setHandler "get:current:user:id", ->
        API.getCurrentUserId()

    UserModel