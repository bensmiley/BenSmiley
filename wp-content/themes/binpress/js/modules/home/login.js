(function() {
  define(['jquery', 'jqueryvalidate', 'bootstrap', 'ladda', 'spin'], function() {
    return $(document).ready(function() {
      var formatLoginData;
      jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
      });
      $('#login-form').validate({
        focusInvalid: false,
        rules: {
          user_email: {
            required: true,
            email: true
          },
          user_pass: {
            required: true
          }
        },
        errorPlacement: function(label, element) {
          var parent;
          $('<span class="errors"></span>').insertAfter(element).append(label);
          parent = $(element).parent('.input-with-icon');
          return parent.removeClass('success-control').addClass('error-control');
        },
        success: function(label, element) {
          var parent;
          parent = $(element).parent('.input-with-icon');
          return parent.removeClass('error-control').addClass('success-control');
        }
      });
      $('#btn-login').click(function() {
        if ($('#login-form').valid()) {
          return $('#login-form').submit(function() {
            var formAction, loginDetails, loginDetailsArray;
            loginDetailsArray = $(this).serializeArray();
            loginDetails = formatLoginData(loginDetailsArray);
            formAction = {
              'action': 'user-login'
            };
            $.extend(loginDetails, formAction);
            return $.post(AJAXURL, loginDetails, function(response) {
              var errorMsg, page, successMsg;
              if (response.code === "OK") {
                successMsg = response.msg;
                $('#display-login-msg').empty();
                $('.ajax-loader').show();
                $('#display-login-msg').append(successMsg);
                page = "/dashboard";
                window.location.href = response.site_url + page;
              }
              if (response.code === "ERROR") {
                errorMsg = response.msg;
                $(' #display-login-msg').empty();
                return $('#display-login-msg').append(errorMsg);
              }
            });
          });
        } else {
          $('#display-login-msg').empty();
          return $('#display-login-msg').append("<div class='alert alert-error'><button class='close' data-dismiss='alert'></button>Please Fill the require fields </div>");
        }
      });
      return formatLoginData = function(serializedDataArray) {
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
