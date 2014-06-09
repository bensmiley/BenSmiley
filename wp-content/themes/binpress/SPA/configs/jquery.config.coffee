define [ 'jquery', 'jqueryvalidate' ], ( jQuery )->

    # FIXME: Move jquery validate config into its own separate file
    jQuery.validator.setDefaults
        debug : true,
        success : "valid"