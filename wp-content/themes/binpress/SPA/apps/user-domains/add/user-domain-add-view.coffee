#include the files for the app
#TODO: remove the backbonesyphon from the file
define ['app'
        'text!apps/user-domains/templates/addUserDomain.html'
        'backbonesyphon'
        'jquery-validate'], (App, addUserDomainTpl)->

    #start the app module
    App.module 'UserDomainAddAppView', (View, App)->

        # Layout for displaying the user domains
        class View.UserDomainAddView extends Marionette.Layout

            className : 'add-user-domain-container'

            template : addUserDomainTpl















