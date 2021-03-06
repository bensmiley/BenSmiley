(function() {
  define(['jquery', 'jqueryvalidate'], function() {
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
      return $('#reset-password-form').validate({
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
    });
  });

}).call(this);
