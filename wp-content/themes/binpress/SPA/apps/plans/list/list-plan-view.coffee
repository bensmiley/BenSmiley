#include the files for the app
define [ 'marionette' ], ( Marionette )->

    # Plans list main layout
    class PlansListLayout extends Marionette.Layout

        template : '<!-- TABS -->
                                    <ul class="nav nav-tabs" id="tab-01">
                                        <li><a href="#domains">Domain Details</a></li>
                                        <li class="active"><a href="javascript:void(0)">Domain Plan</a></li>
                                        <li><a href="#">Statistics</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <!-- Show user domain and add new user domain region -->
                                        <div class="tab-pane active">
                                            <div class="page-title"><i class="icon-custom-left"></i>
                                                <h3>Pricing <span class="semi-bold"> and Plans</span></h3>
                                            </div>
                                            <div id="active-subscription"></div>
                                            <br>
                                            <div id="plans-list"></div>
                                            <div class="clearfix"></div>
                                         </div>
                                        <hr>
                                    </div>
                                      '

        regions :
            activeSubscriptionRegion : '#active-subscription'
            plansListRegion : '#plans-list'

        onShow :->
            activePlan = @$el.find('#active-plan-name').text()
            console.log activePlan

    #current plan view
    class ActiveSubscriptionView extends Marionette.ItemView

        template : ' <div class="col-md-12">
                                        <div class="tiles blue">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="tiles-body">
                                                        <div > ACTIVE PLAN </div>
                                                            <div class="heading">
                                                                <span class="animate-number"
                                                                id="active-plan-name">{{name}}</span>
                                                           </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="tiles-body">
                                                        <div > ACTIVE SINCE </div>
                                                            <div class="heading">
                                                                <span class="animate-number" >{{start_date}}</span>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>'

        className : 'row'

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
            a= Marionette.getOption @,'domainId'
            console.log a



    #View to display plan list
    class PlanListsView extends Marionette.CompositeView

        template : ' <ul class="ca-menu"></ul> '

        itemViewContainer : '.ca-menu'

        itemView : SinglePlanView

        onShow :->
#            linkValue = @$el.find('.plan-link').attr 'href'
            domainId =  Marionette.getOption @,'domainId'
            console.log a
#            newLinkValue = "#{linkValue}/#{domainId}"
#            @$el.find('.plan-link').attr 'href' : newLinkValue


    # return the view instances as objects
    PlansListLayout : PlansListLayout
    ActiveSubscriptionView : ActiveSubscriptionView
    PlanListsView : PlanListsView








