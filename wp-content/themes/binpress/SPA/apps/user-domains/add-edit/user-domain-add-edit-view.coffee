#include the files for the app
define [ 'app'
         'text!apps/user-domains/templates/AddEditUserDomain.html' ], ( App, addEditUserDomainTpl )->

    # Layout for add-edit user domains
    class UserDomainAddEditView extends Marionette.Layout

        className : 'add-user-domain-container'

        template : addEditUserDomainTpl

        events :
            'click #btn-add-edit-user-domain' : ->
                if @$el.find( '#add-edit-user-domain-form' ).valid()
                    #get all serialized data from the form
                    domaindata = Backbone.Syphon.serialize @
                    @trigger "add:edit:user:domain:clicked", domaindata

            'click #show-domain-list' :->
                @trigger "show:domain:list:clicked"


        onShow : ->

            #if no model is passed: show add view
            if _.isUndefined(@model)
                @$el.find('#form-title' ).text 'Add Domain'

            else
                @$el.find('#form-title' ).text 'Edit Domain'


            #validate the add user domain form with the validation rules
            @$el.find( '#add-user-domain-form' ).validate @validationOptions()

        onUserDomainAddUpdate : ->

            #reset the form
            @$el.find('#btn-reset-add-domain').click()

            #show success msg
            @$el.find( '#msg' ).empty()

            if _.isUndefined(@model)
                msg = "Domain Sucessfully Added"
            else
                msg = "Domain Updated Added"

            successhtml = "<div class='alert alert-success'>
                            <button class='close' data-dismiss='alert'>&times;</button>#{msg}<div>"

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

    #return the view instance
    UserDomainAddEditView















