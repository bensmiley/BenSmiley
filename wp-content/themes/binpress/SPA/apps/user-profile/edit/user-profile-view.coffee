#include the files for the app
define [ 'marionette' , 'text!apps/user-profile/templates/userprofile.html' ], ( Marionette, userProfileTpl )->

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
                    @$el.find('.ajax-loader-login' ).show()
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
            @$el.find('.ajax-loader-login' ).hide()
            @$el.find( '#form-msg' ).empty()
            userPassword = @$el.find( '#user_pass' ).val()
            if userPassword != ""
                msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Your user profile updated sucessfully please logout from the account</div>'
            else
                msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Updated User profile</div>'
            @$el.find( '#form-msg' ).append msg

    # return the user profile view instance
    UserProfileView









