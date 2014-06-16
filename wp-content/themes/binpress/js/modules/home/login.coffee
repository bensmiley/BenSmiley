#load the necessary js files for the page
define ['jquery', 'jqueryvalidate', 'bootstrap', 'ladda', 'spin'], ->

    #trigger all action on document load
    $(document).ready ->

        #set the deafults for jQuery validator to prevent form submit
        jQuery.validator.setDefaults
            debug : true,
            success : "valid"

        #validation rules for login form
        $('#login-form').validate
            focusInvalid : false,
            rules :
                user_email :
                    required : true,
                    email : true

                user_pass :
                    required : true


            errorPlacement : (label, element) ->
                $('<span class="errors"></span>').insertAfter(element).append(label)
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('success-control').addClass('error-control')

            success : (label, element) ->
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('error-control').addClass('success-control')

        #action on form submit button click event
        $('#btn-login').click ->

            #check if the login form is valid and passes the validation rules
            if($('#login-form').valid())

                #on successfull validation, submit the login form
                $('#login-form').submit ->

                    #get the details from the login form and convert it to json format
                    loginDetailsArray = $(this).serializeArray()
                    loginDetails = formatLoginData loginDetailsArray

                    #set the form ajax action
                    formAction =
                        'action' : 'user-login'

                    #merge the objects to be passed in ajax call
                    $.extend(loginDetails, formAction)

                    #trigger ajax call and get the response
                    $.post(AJAXURL, loginDetails, (response)->
                        if(response.code == "OK")
                            successMsg = response.msg
                            $('#display-login-msg').empty()
                            $('#btn-login' ).addClass ''
                            $('#display-login-msg').append successMsg
                            $('#btn-login' ).addClass 'ladda-progress'
                            page = "/dashboard"
                            window.location.href = response.site_url + page

                        if(response.code == "ERROR")
                            errorMsg = response.msg
                            $('#display-login-msg').empty()
                            $('#display-login-msg').append errorMsg
                    )
            else
                $('#display-login-msg').empty()
                $('#display-login-msg').append "<div class='alert alert-error'><button class='close' data-dismiss='alert'></button>Please Fill the require fields </div>"

        #converts the form data array into proper key-value format
        #input: format data in array format
        #output: form data in key-value format
        formatLoginData = (serializedDataArray)->
            data = {}
            $.each serializedDataArray, (key, ele)->
                data[ele.name] = ele.value
            data
