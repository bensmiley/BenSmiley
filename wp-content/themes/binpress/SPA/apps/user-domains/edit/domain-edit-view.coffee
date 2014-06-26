#include the files for the app
define [ 'marionette'
         'text!apps/user-domains/templates/AddEditUserDomain.html'
         'text!apps/user-domains/templates/activeSubscription.html' ], ( Marionette, addEditUserDomainTpl,activeSubscriptionTpl )->

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
                    $( '.ajax-loader-login' ).show()

        regions :
            groupsRegion : '#groups-region'
            activeSubscriptionRegion : '#active-subscription'


        onShow : ->
            @$el.find( '.form-title' ).text 'Edit Domain'
            @$el.find( '#domain-groups' ).css 'display' : 'inline'

            #validate the add user domain form with the validation rules
            @$el.find( '#add-edit-user-domain-form' ).validate @validationOptions()

        onDomainUpdated : ->
            #hide the loader
            $( '.ajax-loader-login' ).hide()

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

    #view to show the active subscription plan for the domain
    class ActiveSubscriptionView extends Marionette.ItemView

        template : activeSubscriptionTpl

        className : 'alert alert-info'

        onShow : ->
            if not _.isUndefined @model.get 'pending_subscription'
                @$el.find( '#change-plan' ).hide()
                @$el.find( '.text-muted' ).hide()
                @$el.find( '#pending-subscription' ).show()

        serializeData : ->
            data = super()
            data.active_plan_name = (@model.get 'active_subscription').plan_name
            data.active_plan_price = (@model.get 'active_subscription').price
            data.active_bill_start = (@model.get 'active_subscription').bill_start
            data.active_bill_end = (@model.get 'active_subscription').bill_end

            if not _.isUndefined @model.get 'pending_subscription'
                data.pending_plan_name = (@model.get 'pending_subscription').plan_name
                data.pending_plan_price = (@model.get 'pending_subscription').price
                data.pending_start_date = (@model.get 'pending_subscription').start_date
            data


    #return the view instance
    DomainEditLayout : DomainEditLayout
    ActiveSubscriptionView : ActiveSubscriptionView















