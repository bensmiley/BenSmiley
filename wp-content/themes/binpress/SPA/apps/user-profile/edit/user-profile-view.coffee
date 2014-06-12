#include the files for the app
define [ 'app', 'text!apps/user-profile/templates/userprofile.html' ], ( App, userProfileTpl )->

    # View class for showing user profile
    class UserProfileView extends Marionette.Layout

        className : 'user-profile-container'

        template : userProfileTpl

        tagName : 'form'

        id : "user-profile-form"

        regions :
            userPhotoRegion : '#user-photo'

        events :
            'click #btn-save-user-profile' : ->
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
            userPassword = @$el.find( '#user_pass' ).val()
            if userPassword != ""
                @$el.find( '#form-msg-logout' ).append "<p>Logout of your account</p>"


    # return the user profile view instance
    UserProfileView









