requirejs.config
    urlArgs : "?ver=#{Math.random()}"
    baseUrl : '../wp-content/themes/binpress/js/'
    paths :
        jquery : 'bower_components/jquery/dist/jquery'
        'jquery-validate' : 'bower_components/jquery.validation/dist/jquery.validate'
        bootstrap : 'bower_components/bootstrap/dist/js/bootstrap.min'
    shim:
        'jquery-validate' : ['jquery']
        bootstrap       : ['jquery']

