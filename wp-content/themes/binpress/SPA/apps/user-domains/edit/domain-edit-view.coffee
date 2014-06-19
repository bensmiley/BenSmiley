#include the files for the app
define [ 'marionette'
         'text!apps/user-domains/templates/AddEditUserDomain.html' ], ( Marionette, addEditUserDomainTpl )->

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
            groupsRegion : '#groups-region'
            activeSubscriptionRegion : '#active-subscription'


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

    #view to show the active subscription plan for the domain
    class ActiveSubscriptionView extends Marionette.ItemView

        template : '<h3 class="m-b-20"><span class="semi-bold">Plans Details</span></h3>

                    <div class="grid simple">

                        <dl class="dl-horizontal dl-plan">
                            <dt>Current Plan :</dt>
                            <dd><span class="label label-info">{{name}}</span></dd>
                            <dt>Payement :</dt>
                            <dd>{{price}}/month</dd>
                            <dt>Billing Cycle :</dt>
                            <dd>{{bill_start}} To {{bill_end}}</dd>
                        </dl>

                        <a href="#domains/edit/{{domain_id}}/list-plan" class="btn btn-success btn-block">
                        <i class="icon-ok"></i> Change Plan</a>

                        <div class="clearfix"></div>

                    </div>'

        className : 'alert alert-info'

    #return the view instance
    DomainEditLayout : DomainEditLayout
    ActiveSubscriptionView : ActiveSubscriptionView















