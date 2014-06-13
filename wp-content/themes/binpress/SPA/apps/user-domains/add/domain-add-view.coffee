#include the files for the app
define [ 'app', 'text!apps/user-domains/templates/addEditUserDomain.html' ], ( App, addUserDomainTpl )->

    # Layout for adding the user domains
    class DomainAddView extends Marionette.ItemView

        className : 'add-user-domain-container'

        template : addUserDomainTpl

        events :
            'click #btn-add-edit-user-domain' : ->
                if @$el.find( '#add-edit-user-domain-form' ).valid()
                    #get all serialized data from the form
                    domaindata = Backbone.Syphon.serialize @
                    @trigger "add:domain:clicked", domaindata

            'click #show-domain-list' : ->
                @trigger "show:domain:list:clicked"
        onShow : ->

            #validate the add user domain form with the validation rules
            @$el.find( '#add-edit-user-domain-form' ).validate @validationOptions()

            # set the templates title to add domain
            @$el.find( '#form-title' ).text 'Add Domain'

        onUserDomainAdded : ->
            #reset the form on submit
            @$el.find( '#btn-reset-add-domain' ).click()

            #show success msg
            @$el.find( '#msg' ).empty()
            successhtml = '<div class="alert alert-success">
                            <button class="close" data-dismiss="alert">&times;</button>
                            Domain Sucessfully Added
                        </div>'
            @$el.find( '#msg' ).append successhtml

        validationOptions : ->
            rules :
                post_title :
                    required : true,

                domain_url :
                    required : true,
                    url : true

            messages :
                domain_url : 'Enter valid url'

    DomainAddView















