define ["app", 'backbone'], (App, Backbone) ->
    App.module "Entities.Users", (Users, App, Backbone, Marionette, $, _)->

        #User model
        class Users.UserModel extends Backbone.Model
            name : 'user'
            idAttribute : 'ID'


        # This is current user model (Logged in user model)
        currentUser = new Users.UserModel
        currentUser.set CURRENTUSERDATA


        #model for user data
        user = new Users.UserModel
        user.fetch()

        #PUBLIC API
        API =
            getUser : ->
                user

            getCurrentUser : ->
                currentUser

        #REQUEST HANDLERS
        App.reqres.setHandler "get:user:model", (options = {}) ->
            API.getUser()

        App.reqres.setHandler "get:current:user:model", (options = {}) ->
            API.getCurrentUser()