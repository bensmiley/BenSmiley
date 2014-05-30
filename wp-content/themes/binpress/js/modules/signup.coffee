define ['jquery', 'jquery-validate'], ->

    $(document).ready ->
        jQuery.validator.setDefaults
            debug: true,
            success: "valid"

        $('#signup-form').validate
            focusInvalid: false,
            ignore: "",
            rules:
                user_name:
                    minlength: 4,
                    required: true

                user_email:
                    required: true,
                    email: true

                user_pass:
                    required: true,
                    minlength: 5

                user_confirm_password:
                    required: true,
                    equalTo: "#user_pass"

                agree_tc:
                    required: true


            errorPlacement: (label, element) ->
                $('<span class="error"></span>').insertAfter(element).append(label)
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('success-control').addClass('error-control')

            success: (label, element) ->
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('error-control').addClass('success-control')

        $('#btn-signup').click ->

            if($('#signup-form').valid())
                $('#signup-form').submit ->
                    #get the details from the sign up form and convert it to json format
                    signUpDetailsArray = $(this).serializeArray();
                    signUpDetails= formatSignUpData signUpDetailsArray

                    #set the form ajax action
                    formAction =
                        'action' : 'new-user-signup'

                    #merge the objects to be passed in ajax call
                    $.extend(signUpDetails, formAction);

                    $.post(AJAXURL, signUpDetails , (response)->
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

        formatSignUpData = (serializedDataArray)->
            data = {}
            $.each serializedDataArray,( key, ele )->
                data[ele.name] = ele.value
            data

