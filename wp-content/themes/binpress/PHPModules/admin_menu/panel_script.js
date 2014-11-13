jQuery(document).ready(function(){
	// add multiple select / deselect functionality
    jQuery("#selectall").click(function () {
          jQuery('.case').attr('checked', this.checked);
    });
 
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    jQuery(".case").click(function(){
 
        if(jQuery(".case").length == jQuery(".case:checked").length) {
            jQuery("#selectall").attr("checked", "checked");
        } else {
            jQuery("#selectall").removeAttr("checked");
        }
 
    });

	jQuery('#btn-save-users').click(function() {
	var arr, selectedlanguage;

	arr = jQuery("td input[type='checkbox']");

	selectedUsers = new Array();

	jQuery.each(arr, function() {
		if (this.checked) {
			selectedUsers.push(this.value);
		}
	});

	selectedUsers = selectedUsers.join(",");
      
     var formAction, user_email, user_passwod;
        formAction = {
          'action': 'update-billing-users-options',
          'selected_users': selectedUsers
        };
        return jQuery.post(ajaxurl, formAction, function(response) {
          var errorMsg, successMsg;

            successMsg = response.msg;
            jQuery('#setting-error-settings_updated').empty();
            jQuery('#setting-error-settings_updated').append(successMsg);
         
          // if (response.code === "ERROR") {
          //   errorMsg = response.msg;
          //   $('#display-reset-msg').empty();
          //   return $('#display-reset-msg').append(errorMsg);
          // }
        });
     
    });

});

