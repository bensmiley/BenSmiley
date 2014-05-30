define ['jquery', 'jquery-validate'], ->
    $(document).ready ->
        jQuery.validator.setDefaults
            debug: true,
            success: "valid"

        $('#forgot-password-form').validate
            focusInvalid: false,
            ignore: "",
            rules:
                user_email:
                    required: true,
                    email: true


            errorPlacement: (label, element) ->
                $('<span class="error"></span>').insertAfter(element).append(label)
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('success-control').addClass('error-control')

            success: (label, element) ->
                parent = $(element).parent('.input-with-icon')
                parent.removeClass('error-control').addClass('success-control')

        $('#btn-forgot-pass').click ->

            if($('#signup-form').valid())
                $('#signup-form').submit ->
                    #get the details from the sign up form and convert it to json format
                    forgotPasswordDetailsArray = $(this).serializeArray();
                    forgotPasswordDetails= formatforgotPasswordData forgotPasswordDetailsArray

                    #set the form ajax action
                    formAction =
                        'action' : 'new-user-signup'

                    #merge the objects to be passed in ajax call
                    $.extend(forgotPasswordDetails, formAction);

                    $.post(AJAXURL, forgotPasswordDetails , (response)->
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

        formatforgotPasswordData = (serializedDataArray)->
            data = {}
            $.each serializedDataArray,( key, ele )->
                data[ele.name] = ele.value
            data

