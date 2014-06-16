#include the files for the app
define [ 'marionette'
         'text!apps/user-domains/templates/addEditUserDomain.html' ], ( Marionette, addEditUserDomainTpl )->

    # Layout for add-edit user domains
    class DomainEditLayout extends Marionette.Layout

        className : 'add-user-domain-container'

        template : addEditUserDomainTpl

        events :
            'click #btn-add-edit-user-domain' : ->
                if @$el.find( '#add-edit-user-domain-form' ).valid()
                    #get all serialized data from the form
                    domaindata = Backbone.Syphon.serialize @
                    @trigger "edit:domain:clicked", domaindata

        regions :
            addDomainGroupRegion : '#add-domain-groups'
            listDomainGroupRegion : '#list-domain-groups'


        onShow : ->

            @$el.find( '#form-title' ).text 'Edit Domain'
            @$el.find( '#domain-groups' ).css 'display' : 'inline'

            #validate the add user domain form with the validation rules
            @$el.find( '#add-edit-user-domain-form' ).validate @validationOptions()

        onDomainUpdated : ->

            #reset the form
            @$el.find( '#btn-reset-add-domain' ).click()

            #show success msg
            @$el.find( '#msg' ).empty()

            successhtml = "<div class='alert alert-success'>
                           <button class='close' data-dismiss='alert'>&times;</button>
                           Domain Updated Sucessfully<div>"

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

    #return the view instance
    DomainEditLayout















