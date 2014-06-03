define ["app", 'backbone'], (App, Backbone) ->
    App.module "Entities.Users", (Users, App, Backbone, Marionette, $, _)->

        #User model
        class Users.UserModel extends Backbone.Model
            name : 'user'
            idAttribute : 'ID'


        user = new Users.UserModel
        user.fetch()

        #PUBLIC API
        API =
            getUser : ->
                user

        #REQUEST HANDLERS
        App.reqres.setHandler "get:user:model", (options = {}) ->
            API.getUser()