#load the necessary js files for the page
define ['jquery', 'jqueryvalidate'], ->

    #trigger all action on document load
    $(document).ready ->

        #set the deafults for jQuery validator to prevent form submit
        jQuery.validator.setDefaults
            debug : true,
            success : "valid"

        #validate the forgot password modal with the validation rules
        $('#forgot-password-form').validate
            focusInvalid : false,
            rules :
                userEmail :
                    required : true,
                    email : true

        #error msg display
            errorPlacement : (label, element)->
                $('<span class="error"></span>').insertAfter(element).append(label)
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('success-control').addClass('error-control')

        #action on successful validation
            success : (label, element) ->
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('error-control').addClass('success-control')

        #action on  forgot password form, submit button click
        $('#btn-forgot-pass').click ->

            #check if forgot password form is valid and passes validation rules
            if($('#forgot-password-form').valid())

                #get the user email
                user_email = $('#userEmail').val()

                #set the form action
                formAction =
                    'action' : 'reset-user-password'
                    'user_email' : user_email

                #trigger AJAX call
                $.post(AJAXURL, formAction, (response)->
                    if(response.code == "OK")
                        successMsg = response.msg
                        $('#display-forgot-msg').empty()
                        $('#display-forgot-msg').append successMsg
                        $('#btn-forgot-form-reset').click()

                    if(response.code == "ERROR")
                        errorMsg = response.msg
                        $('#display-forgot-msg').empty()
                        $('#display-forgot-msg').append errorMsg
                )

        #validate the reset  password form with the validation rules
        $('#reset-password-form').validate
            focusInvalid : false,
            rules :
                user_email :
                    required : true,
                    email : true

                user_pass :
                    required : true,
                    minlength : 5

                confirm_password :
                    required : true,
                    equalTo : "#user_pass"

        #error msg display
            errorPlacement : (label, element) ->
                $('<span class="error"></span>').insertAfter(element).append(label)
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('success-control').addClass('error-control')

        #action on successful validation
            success : (label, element) ->
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('error-control').addClass('success-control')

