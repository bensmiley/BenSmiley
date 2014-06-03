define ['jquery','jquery-validate'],(jQuery)->

    #set the deafults for jQuery validator to prevent form submit
    jQuery.validator.setDefaults
        debug : true,
        success : "valid"