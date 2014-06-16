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
                            <div id="current-plan"></div>
                            <br>
                            <div id="plans-list"></div>
                            <div class="clearfix"></div>
                         </div>
                        <hr>
                    </div>
                      '

        regions :
            currentPlanRegion : '#current-plan'
            plansListRegion : '#plans-list'

    #current plan view
    class CurrentPlanView extends Marionette.ItemView

        template : ' <div class="col-md-12">
                        <div class="tiles blue">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="tiles-body">
                                        <div > ACTIVE PLAN </div>
                                            <div class="heading">
                                                <span class="animate-number" >{{plan_name}}</span>
                                           </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="tiles-body">
                                        <div > ACTIVE SINCE </div>
                                            <div class="heading">
                                                <span class="animate-number" >{{start_time}}</span>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>'

        className : 'row'

    #view for each plan
    class SinglePlanView extends Marionette.ItemView
        template : '<a href="javascript:void(0)">
                      <div class="grid simple">
                         <h4 class="bold text-center">{{name}}<br>
                         <small class="text-danger" >Rs.{{price}}/month</small></h4>
                         <hr>
                          <div class="grid-body no-border">
                              <ul>
                                  <li>{{description}} </li>
                              </ul>
                          </div>
                          <a href="#change-plan/{{plan_id}}" class="btn btn-block btn-primary ca-sub">Subscribe</a>
                      </div>
                    </a>'
        tagName : 'li'

        className : 'plans'

        serializeData :->
            data = super()
            console.log data
            data

    #View to display plan list
    class PlanListsView extends Marionette.CompositeView

        template : ' <ul class="ca-menu"></ul> '

        itemViewContainer : '.ca-menu'

        itemView : SinglePlanView

    # return the view instances as objects
    PlansListLayout : PlansListLayout
    CurrentPlanView : CurrentPlanView
    PlanListsView : PlanListsView








