requirejs.config
    urlArgs : "?ver=#{Math.random()}"
    baseUrl : 'http://localhost/bensmiley/wp-content/themes/binpress/js/'
    paths :
        jquery : 'bower_components/jquery/dist/jquery'
        jqueryvalidate : 'bower_components/jquery.validation/dist/jquery.validate'
        bootstrap : 'bower_components/bootstrap/dist/js/bootstrap.min'
    shim :
        jqueryvalidate : [ 'jquery' ]
        bootstrap : [ 'jquery' ]

