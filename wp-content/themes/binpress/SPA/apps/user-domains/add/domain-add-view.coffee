#include the files for the app
define [ 'marionette'
         'text!apps/user-domains/templates/AddEditUserDomain.html'
         'additionalmethods' ], ( Marionette, addUserDomainTpl, additionalmethods )->

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
                    $( '.ajax-loader-login' ).show()

        onShow : ->

            #validate the add user domain form with the validation rules
            @$el.find( '#add-edit-user-domain-form' ).validate @validationOptions()

            # set the templates title to add domain
            @$el.find( '.form-title' ).text 'Add Domain'

            #hide tabs,delete button and api key box
            @$el.find( '#tabs' ).hide()
            @$el.find( '#btn-delete-domain' ).hide()
            @$el.find( '#apikey-box' ).hide()

        onUserDomainAdded : ( domainId ) ->
            #reset the form on submit
            @$el.find( '#btn-reset-add-domain' ).click()

            #hide the loader
            $( '.ajax-loader-login' ).hide()

            #show success msg
            @$el.find( '#msg' ).empty()
            successhtml = '<div class="alert alert-success">
                                            <button class="close" data-dismiss="alert">&times;</button>
                                            Domain Sucessfully Added
                                        </div>'
            @$el.find( '#msg' ).append successhtml

            #redirect to edit of the domain added
            mainUrl = window.location.href.replace Backbone.history.getFragment(), ''
            redirect_url = "#{mainUrl}domains/edit/#{domainId}"
            _.delay ->
                window.location.href = redirect_url
            , 1000

        onUserDomainAddError : ( errorMsg ) ->
            #hide the loader
            $( '.ajax-loader-login' ).hide()

            #show error msg
            @$el.find( '#msg' ).empty()
            successhtml = "<div class='alert alert-error'>
                                        <button class='close' data-dismiss='alert'>&times;</button>
                                        #{errorMsg}
                                        </div>"
            @$el.find( '#msg' ).append successhtml

        validationOptions : ->
            rules :
                post_title :
                    required : true,

                domain_url :
                    required : true,
                    domain : true

            messages :
                domain_url : 'Invalid domain name'

    DomainAddView















