#include the files for the app

define [ 'app', 'text!apps/user-profile/templates/userprofile.html' ], ( App, userProfileTpl )->

    #start the app module
    App.module 'UserProfileAppView', ( View, App )->

        # View class for showing user profile
        class View.UserProfileView extends Marionette.Layout

            className : 'user-profile-container'

            template : userProfileTpl

            tagName : 'form'

            id : "user-profile-form"

            regions :
                userPhotoRegion : '#user-photo'

            events :
                'click #save-user-profile' : ->
                    #check if the form is valid
                    if @$el.valid()
                        #get all serialized data from the form
                        userdata = Backbone.Syphon.serialize @
                        @trigger "save:user:profile:clicked", userdata
            onShow : ->

                #validate the user profile form with the validation rules
                @$el.validate @validationOptions()

            validationOptions : ->
                rules :
                    display_name :
                        required : true,

                    user_email :
                        required : true,
                        email : true

                    user_pass :
                        minlength : 5

                    confirm_password :
                        equalTo : "#user_pass"
                messages :
                    user_name : 'Enter valid user name'

            onUserProfileUpdated : ->
                @$el.find( '#form-msg' ).empty()
                @$el.find( '#form-msg' ).append "<p>Updated User profile</p>"









