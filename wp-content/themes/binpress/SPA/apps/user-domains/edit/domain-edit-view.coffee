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
                    $( '.ajax-loader-login' ).show()

        regions :
            groupsRegion : '#groups-region'
            activeSubscriptionRegion : '#active-subscription'


        onShow : ->
            @$el.find( '#form-title' ).text 'Edit Domain'
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

        template : '<h3 class="m-b-20"><span class="semi-bold">Plans Details</span></h3>

                            <div class="grid simple">

                                <dl class="dl-horizontal dl-plan">
                                    <dt>Current Plan :</dt>
                                    <dd><span class="label label-info">{{active_plan_name}}</span></dd>
                                    <dt>Billing Amount :</dt>
                                    <dd>{{active_plan_price}}/month</dd>
                                    <dt>Billing Cycle :</dt>
                                    <dd>{{active_bill_start}} To {{active_bill_end}}</dd>
                                </dl>

                                <a href="#domains/edit/{{domain_id}}/list-plan" class="btn btn-success btn-block"
                                id="change-plan">
                                <i class="icon-ok"></i> Change Plan</a>

                                <div class="clearfix"></div>
                                <br>
                                <div id="pending-subscription" style="display: none">
                                <dl class="dl-horizontal dl-plan" >
                                    <dt>Future Plan :</dt>
                                    <dd><span class="label label-info">{{pending_plan_name}}</span></dd>
                                    <dt>Billing Amount :</dt>
                                    <dd>{{pending_plan_price}}/month</dd>
                                    <dt>Billing Start :</dt>
                                    <dd>{{pending_start_date}}</dd>
                                </dl>

                                <a href="javascript:void(0)" class="btn btn-success btn-block"
                                id="change-plan">
                                <i class="icon-ok"></i> Cancel Plan</a>
                                </div>
                                <div class="text-muted">Avail more features by upgrading your plan.
                                 Click change plan to view the available plans</div>
                            </div>'

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















