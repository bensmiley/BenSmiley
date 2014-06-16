(function() {
  define(['jquery', 'jqueryvalidate'], function() {
    return $(document).ready(function() {
      var formatSignUpData;
      jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
      });
      $('#signup-form').validate({
        focusInvalid: false,
        ignore: "",
        rules: {
          user_name: {
            minlength: 4,
            required: true
          },
          user_email: {
            required: true,
            email: true
          },
          user_pass: {
            required: true,
            minlength: 5
          },
          user_confirm_password: {
            required: true,
            equalTo: "#user_pass"
          },
          agree_tc: {
            required: true
          }
        },
        errorPlacement: function(label, element) {
          var parent;
          $('<span class="error"></span>').insertAfter(element.parent()).append(label);
          parent = $(element).parent('.input-with-icon').parent();
          return parent.removeClass('success-control').addClass('error-control');
        },
        success: function(label, element) {
          var parent;
          parent = $(element).parent('.input-with-icon');
          return parent.removeClass('error-control').addClass('success-control');
        }
      });
      $('#btn-signup').click(function() {
        if ($('#signup-form').valid()) {
          return $('#signup-form').submit(function() {
            var formAction, signUpDetails, signUpDetailsArray;
            signUpDetailsArray = $(this).serializeArray();
            signUpDetails = formatSignUpData(signUpDetailsArray);
            formAction = {
              'action': 'new-user-signup'
            };
            $.extend(signUpDetails, formAction);
            return $.post(AJAXURL, signUpDetails, function(response) {
              var errorMsg, successMsg;
              if (response.code === "OK") {
                successMsg = response.msg;
                $('.ajax-loader').show();
                $('#display-msg').empty();
                $('#display-msg').append(successMsg);
                $('#btn-signup-form-reset').click();
              }
              if (response.code === "ERROR") {
                errorMsg = response.msg;
                $('#display-msg').empty();
                return $('#display-msg').append(errorMsg);
              }
            });
          });
        } else {
          $('#display-msg').empty();
          return $('#display-msg').append("<div class='alert alert-error'><button class='close' data-dismiss='alert'></button>Please Fill the require fields </div>");
        }
      });
      return formatSignUpData = function(serializedDataArray) {
        var data;
        data = {};
        $.each(serializedDataArray, function(key, ele) {
          return data[ele.name] = ele.value;
        });
        return data;
      };
    });
  });

}).call(this);
