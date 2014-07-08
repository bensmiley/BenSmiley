#include the files for the app
define [ 'marionette', 'text!apps/plans/templates/listPlanView.html' ], ( Marionette, listPlanViewTpl )->

    #view for each plan
    class SinglePlanView extends Marionette.ItemView
        template : '<div class="text-center">
                                        <h4 class="title1">{{plan_name}}</h4>
                                        <div class="content1" >
										 <div class="price"><sup>&#36;</sup><span>{{price}}</span><sub>/month</sub>
										 </div>
										</div>
                                        <ul class="features1">
                                            <li>{{description}} </li>
                                        </ul>
                                        <div class="pt-footer">
										<a href="#change-plan/{{plan_id}}" class="plan-link">Subscribe</a>
										</div>
                                      </div>'
        tagName : 'li'

        className : 'plans'

        onShow : ->
            #append domain id to link
            linkValue = @$el.find( '.plan-link' ).attr 'href'
            domainId = Marionette.getOption @, 'domainId'
            newLinkValue = "#{linkValue}/#{domainId}"
            @$el.find( '.plan-link' ).attr 'href' : newLinkValue

            #highlight active plan
            activePlanName = Marionette.getOption @, 'activePlanName'
            planName = @model.get 'plan_name'
            if activePlanName == planName
                @$el.addClass 'highlight'
                @$el.find( '.plan-link' ).attr 'href' : 'javascript:void(0)'


    # Plans list main view
    class PlansListView extends Marionette.CompositeView

        template : listPlanViewTpl

        itemViewContainer : '.ca-menu'

        itemView : SinglePlanView

        serializeData : ->
            data = super()
            data.plan_name = (@model.get 'active_subscription').plan_name
            data.start_date = (@model.get 'active_subscription').start_date
            data

        itemViewOptions : ->
            planName = (@model.get 'active_subscription').plan_name
            domainID = Marionette.getOption @, 'domainId'
            activePlanName : planName
            domainId : domainID


    # return the view instances as objects
    PlansListView








