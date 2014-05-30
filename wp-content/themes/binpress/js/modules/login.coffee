define ['jquery', 'jquery-validate', 'bootstrap'], ->
    $(document).ready ->
        jQuery.validator.setDefaults
            debug: true,
            success: "valid"

        $('#login-form').validate
            focusInvalid: false,
            rules:
                user_email:
                    required: true,
                    email: true

                user_pass:
                    required: true


            errorPlacement: (label, element) ->
                $('<span class="error"></span>').insertAfter(element).append(label)
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('success-control').addClass('error-control')

            success: (label, element) ->
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('error-control').addClass('success-control')

        $('#btn-login').click ->
            if($('#login-form').valid())
                $('#login-form').submit ->
                    #get the details from the sign up form and convert it to json format
                    loginDetailsArray = $(this).serializeArray();
                    loginDetails = formatLoginData loginDetailsArray

                    #set the form ajax action
                    formAction =
                        'action': 'user-login'

                    #merge the objects to be passed in ajax call
                    $.extend(loginDetails, formAction);

                    $.post(AJAXURL, loginDetails, (response)->
                        if(response.code == "OK")
                            successMsg = response.msg
                            $('#display-login-msg').empty()
                            $('#display-login-msg').append successMsg
                            page = "/dashboard"
                            window.location.href = response.site_url+page
                        if(response.code == "ERROR")
                            errorMsg = response.msg
                            $(' #display-login-msg').empty()
                            $('#display-login-msg').append errorMsg
                    )
            else
                $('#display-login-msg').empty()
                $('#display-login-msg').append "<p>not valid form</p>"

        formatLoginData = (serializedDataArray)->
            data = {}
            $.each serializedDataArray, (key, ele)->
                data[ele.name] = ele.value
            data
