define [ 'jquery', 'jqueryvalidate' ], ( jQuery )->

	# FIXME: Move jquery validate config into its own separate file
	jQuery.validator.setDefaults
		debug : true,
		success : "valid"

	jQuery.validator.addMethod "domain",((nname)->
		name = nname.replace 'http://','' 
		nname = nname.replace 'https://','' 

		mai = nname;
		val = true;

		dot = mai.lastIndexOf(".");
		dname = mai.substring(0,dot);
		ext = mai.substring(dot,mai.length);
				 
		if(dot>2 && dot<57)
			return true;

		return false;

	), 'Invalid domain name.'