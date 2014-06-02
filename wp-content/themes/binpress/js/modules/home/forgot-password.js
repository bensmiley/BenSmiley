// Generated by CoffeeScript 1.7.1
define(['jquery', 'jquery-validate'], function() {
  return $(document).ready(function() {
    jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });
    $('#forgot-password-form').validate({
      focusInvalid: false,
      rules: {
        userEmail: {
          required: true,
          email: true
        }
      },
      errorPlacement: function(label, element) {
        var parent;
        $('<span class="error"></span>').insertAfter(element).append(label);
        parent = $(element).parent('.input-with-icon');
        return parent.removeClass('success-control').addClass('error-control');
      },
      success: function(label, element) {
        var parent;
        parent = $(element).parent('.input-with-icon');
        return parent.removeClass('error-control').addClass('success-control');
      }
    });
    $('#btn-forgot-pass').click(function() {
      var formAction, user_email;
      if ($('#forgot-password-form').valid()) {
        user_email = $('#userEmail').val();
        formAction = {
          'action': 'reset-user-password',
          'user_email': user_email
        };
        return $.post(AJAXURL, formAction, function(response) {
          var errorMsg, successMsg;
          if (response.code === "OK") {
            successMsg = response.msg;
            $('#display-forgot-msg').empty();
            $('#display-forgot-msg').append(successMsg);
            $('#btn-forgot-form-reset').click();
          }
          if (response.code === "ERROR") {
            errorMsg = response.msg;
            $('#display-forgot-msg').empty();
            return $('#display-forgot-msg').append(errorMsg);
          }
        });
      }
    });
    $('#reset-password-form').validate({
      focusInvalid: false,
      rules: {
        user_email: {
          required: true,
          email: true
        },
        user_pass: {
          required: true,
          minlength: 5
        },
        confirm_password: {
          required: true,
          equalTo: "#user_pass"
        }
      },
      errorPlacement: function(label, element) {
        var parent;
        $('<span class="error"></span>').insertAfter(element).append(label);
        parent = $(element).parent('.input-with-icon');
        return parent.removeClass('success-control').addClass('error-control');
      },
      success: function(label, element) {
        var parent;
        parent = $(element).parent('.input-with-icon');
        return parent.removeClass('error-control').addClass('success-control');
      }
    });
    return $('#btn-reset-password').click(function() {
      var formAction, user_email, user_passwod;
      if ($('#reset-password-form').valid()) {
        user_email = $('#user_email').val();
        user_passwod = $('#user_pass').val();
        formAction = {
          'action': 'change-password',
          'user_email': user_email,
          'user_pass': user_passwod
        };
        return $.post(AJAXURL, formAction, function(response) {
          var errorMsg, successMsg;
          if (response.code === "OK") {
            successMsg = response.msg;
            $('#display-reset-msg').empty();
            $('#display-reset-msg').append(successMsg);
            $('#btn-reset-form').click();
          }
          if (response.code === "ERROR") {
            errorMsg = response.msg;
            $('#display-reset-msg').empty();
            return $('#display-reset-msg').append(errorMsg);
          }
        });
      }
    });
  });
});

//# sourceMappingURL=forgot-password.map
