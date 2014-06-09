#load the necessary js files for the page
define ['jquery', 'jqueryvalidate'], ->

    #trigger all action on document load
    $(document).ready ->

        #set the deafults for jQuery validator to prevent form submit
        jQuery.validator.setDefaults
            debug : true,
            success : "valid"

        #validation rules for sign up form
        $('#signup-form').validate
            focusInvalid : false,
            ignore : "",
            rules :
                user_name :
                    minlength : 4,
                    required : true

                user_email :
                    required : true,
                    email : true

                user_pass :
                    required : true,
                    minlength : 5

                user_confirm_password :
                    required : true,
                    equalTo : "#user_pass"

                agree_tc :
                    required : true

            errorPlacement : (label, element) ->
                $('<span class="error"></span>').insertAfter(element).append(label)
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('success-control').addClass('error-control')

            success : (label, element) ->
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('error-control').addClass('success-control')

        #action on form submit button click event
        $('#btn-signup').click ->

            #check if the sign up form is valid and passes the validation rules
            if($('#signup-form').valid())

                #on successfull validation, submit the sign up form
                $('#signup-form').submit ->

                    #get the details from the sign up form and convert it to json format
                    signUpDetailsArray = $(this).serializeArray()
                    signUpDetails = formatSignUpData signUpDetailsArray

                    #set the form ajax action
                    formAction =
                        'action' : 'new-user-signup'

                    #merge the objects to be passed in ajax call
                    $.extend(signUpDetails, formAction)

                    #trigger ajax call and get the response
                    $.post(AJAXURL, signUpDetails, (response)->
                        if(response.code == "OK")
                            successMsg = response.msg
                            $('#display-msg').empty()
                            $('#display-msg').append successMsg
                            $('#btn-signup-form-reset').click()

                        if(response.code == "ERROR")
                            errorMsg = response.msg
                            $('#display-msg').empty()
                            $('#display-msg').append errorMsg
                    )
            else
                $('#display-msg').empty()
                $('#display-msg').append "<p>not valid form</p>"

        #converts the form data array into proper key-value format
        #input: format data in array format
        #output: form data in key-value format
        formatSignUpData = (serializedDataArray)->
            data = {}
            $.each serializedDataArray, (key, ele)->
                data[ele.name] = ele.value
            data

