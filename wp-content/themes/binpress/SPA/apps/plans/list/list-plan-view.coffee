#include the files for the app
define [ 'marionette', 'text!apps/plans/templates/listPlanView.html' ], ( Marionette, listPlanViewTpl )->

    #view for each plan
    class SinglePlanView extends Marionette.ItemView
        template : '<div>
                     <div class="grid simple">
                        <h4 class="bold text-center plan-name">{{name}}<br>
                        <small class="text-danger" >Rs.{{price}}/month</small>
                        </h4>
                        <hr>
                         <div class="grid-body no-border">
                             <ul>
                                <li>{{description}} </li>
                             </ul>
                         </div>
                     </div>
                     <a href="#change-plan/{{plan_id}}"
                         class="btn btn-block btn-primary ca-sub plan-link">Subscribe</a>
                      </div>
                                           '
        tagName : 'li'

        className : 'plans'

        onShow :->
            activePlanName = Marionette.getOption @, 'activePlanName'
            planName =  @model.get 'name'
            if activePlanName == planName
                @$el.find('.plan-link').addClass 'hightlighted'


    # Plans list main view
    class PlansListView extends Marionette.CompositeView

        template : listPlanViewTpl

        itemViewContainer : '.ca-menu'

        itemView : SinglePlanView

        itemViewOptions :->

            activePlanName : @model.get 'name'


    # return the view instances as objects
    PlansListView








