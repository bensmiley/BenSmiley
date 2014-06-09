define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->
    class UserModel extends Backbone.Model

        name : 'user'

        idAttribute : 'ID'

    # This is current user model (Logged in user model)
    currentUser = new UserModel
    currentUser.set CURRENTUSERDATA


    #PUBLIC API
    API =
        getCurrentUser : ->
            currentUser

    #Handlers
    msgbus.reqres.setHandler "get:current:user:model", ->
        API.getCurrentUser()

    UserModel