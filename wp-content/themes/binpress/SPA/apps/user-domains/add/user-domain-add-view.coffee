#TODO: add proper comments
#include the files for the app
define [ 'app', 'text!apps/user-domains/templates/addUserDomain.html' ], ( App, addUserDomainTpl )->

    # Layout for adding the user domains
    class UserDomainAddView extends Marionette.Layout

        className : 'add-user-domain-container'

        template : addUserDomainTpl

        events :
            'click #btn-add-user-domain' : ->
                if @$el.find( '#add-user-domain-form' ).valid()
                    #get all serialized data from the form
                    domaindata = Backbone.Syphon.serialize @
                    @trigger "add:user:domain:clicked", domaindata
        onShow : ->

            #validate the add user domain form with the validation rules
            @$el.find( '#add-user-domain-form' ).validate @validationOptions()

        onUserDomainAdded : ->
            @$el.find('#btn-reset-add-domain').click()
            @$el.find( '#msg' ).empty()
            successhtml = '<div class="alert alert-success">
                                <button class="close" data-dismiss="alert">&times;</button>
                                Domain Sucessfully Added
                            </div>'
            @$el.find( '#msg' ).append successhtml

        validationOptions : ->
            rules :
                domain_name :
                    required : true,

                domain_url :
                    required : true,
                    url : true

            messages :
                domain_url : 'Enter valid url'

    UserDomainAddView















