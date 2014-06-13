#include the files for the app
define [ 'app'
         'text!apps/user-domains/templates/addEditUserDomain.html' ], ( App, addEditUserDomainTpl )->

    # Layout for add-edit user domains
    class DomainEditView extends Marionette.Layout

        className : 'add-user-domain-container'

        template : addEditUserDomainTpl

        events :
            'click #btn-add-edit-user-domain' : ->
                if @$el.find( '#add-edit-user-domain-form' ).valid()
                    #get all serialized data from the form
                    domaindata = Backbone.Syphon.serialize @
                    @trigger "add:edit:user:domain:clicked", domaindata

            'click #show-domain-list' : ->
                @trigger "show:domain:list:clicked"


        regions :
            addDomainGroupRegion : '#add-domain-group'


        onShow : ->

            @$el.find( '#form-title' ).text 'Edit Domain'
            @$el.find( '#domain-groups' ).css 'display' : 'inline'

            #validate the add user domain form with the validation rules
            @$el.find( '#add-edit-user-domain-form' ).validate @validationOptions()

        onUserDomainAddUpdate : ->

            #reset the form
            @$el.find( '#btn-reset-add-domain' ).click()

            #show success msg
            @$el.find( '#msg' ).empty()

            if _.isUndefined( @model )
                msg = "Domain Sucessfully Added"
            else
                msg = "Domain Updated Sucessfully"

            successhtml = "<div class='alert alert-success'>
                                                    <button class='close' data-dismiss='alert'>&times;</button>#{msg}<div>"

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
    DomainEditView














