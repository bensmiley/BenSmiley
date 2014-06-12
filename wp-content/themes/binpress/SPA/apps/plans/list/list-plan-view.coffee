#include the files for the app
define [ 'app', 'text!apps/user-profile/templates/userprofile.html' ], ( App, userProfileTpl )->

    # Plans list main layout
    class PlansListLayout extends Marionette.Layout

        template : '<div class="page-header">
                                            <h1 class="normaltext-center">
                                                <span class="p-r-10">Pricing and Plans</span>
                                            </h1>
                                        </div>
                                        <div id="current-plan"></div>
                                        <br>
                                        <div id="plans-list"></div>
                                        <div class="clearfix"></div>'

        regions :
            currentPlanRegion : '#current-plan'
            plansListRegion : '#plans-list'

    class CurrentPlanView extends Marionette.ItemView

        template : '         <div class="col-md-12">
                                        <div class="tiles blue">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="tiles-body">
                                                        <div > ACTIVE PLAN </div>
                                                            <div class="heading">
                                                                <span class="animate-number" >Free</span>
                                                                <a href="#" class="white-txt">
                                                                    <small class="tiles-title">
                                                                    (Deactivite Plan)</small>
                                                                </a>
                                                           </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="tiles-body">
                                                        <div > ACTIVE SINCE </div>
                                                            <div class="heading">
                                                                <span class="animate-number" >09/12/2014</span>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>'

        className : 'row'

    class PlanListView extends Marionette.ItemView

        template : ' <li class="plans">
                                        <a href="#payment">
                                          <div class="grid simple">
                                             <h4 class="bold text-center">Pro<br><small class="text-danger" > US$40.00/month</small></h4>
                                             <hr>
                                              <div class="grid-body no-border">
                                                  <ul>
                                                      <li>Lorem ipsum dolor sit </li>
                                                      <li>Consectetur adipiscing </li>
                                                      <li>Integer molestie lorem at </li>
                                                      <li>Facilisis in pretium nisl </li>
                                                      <li>Nulla volutpat aliquam </li>
                                                  </ul>
                                              </div>
                                              <button class="btn btn-block btn-primary ca-sub" type="button">Subscribe</button>
                                          </div>
                                        </a>
                                    </li>
                                    <li class="plans ">
                                        <a href="#">
                                             <div class="grid simple ">
                                             <h4 class="bold text-center">Silver<br><small class="text-danger" > US$80.00/month</small></h4>
                                             <hr>
                                              <div class="grid-body no-border">
                                                  <ul>
                                                      <li>Lorem ipsum dolor sit </li>
                                                      <li>Consectetur adipiscing </li>
                                                      <li>Integer molestie lorem at </li>
                                                      <li>Facilisis in pretium nisl </li>
                                                      <li>Nulla volutpat aliquam </li>
                                                  </ul>
                                              </div>
                                              <button class="btn btn-block btn-primary ca-sub" type="button">Subscribe</button>
                                          </div>
                                        </a>
                                    </li>
                                    <li class="plans">
                                        <a href="#">
                                           <div class="grid simple">
                                             <h4 class="bold text-center">Gold<br><small class="text-danger" > US$110.00/month</small></h4>
                                             <hr>
                                              <div class="grid-body no-border">
                                                  <ul>
                                                      <li>Lorem ipsum dolor sit </li>
                                                      <li>Consectetur adipiscing </li>
                                                      <li>Integer molestie lorem at </li>
                                                      <li>Facilisis in pretium nisl </li>
                                                      <li>Nulla volutpat aliquam </li>
                                                  </ul>
                                              </div>
                                              <button class="btn btn-block btn-primary ca-sub" type="button">Subscribe</button>
                                          </div>
                                        </a>
                                    </li>
                                    <li class="plans">
                                        <a href="#">
                                             <div class="grid simple">
                                             <h4 class="bold text-center">Platinum<br><small class="text-danger" > US$254.00/month</small></h4>
                                             <hr>
                                              <div class="grid-body no-border">
                                                  <ul>
                                                      <li>Lorem ipsum dolor sit </li>
                                                      <li>Consectetur adipiscing </li>
                                                      <li>Integer molestie lorem at </li>
                                                      <li>Facilisis in pretium nisl </li>
                                                      <li>Nulla volutpat aliquam </li>
                                                  </ul>
                                              </div>
                                              <button class="btn btn-block btn-primary ca-sub" type="button">Subscribe</button>
                                          </div>
                                        </a>
                                    </li>
                                    <li class="plans highlight">
                                        <a href="#">
                                            <div class="grid simple">
                                             <h4 class="bold text-center">Free<br><small class="text-danger" > US$0.00/month</small></h4>
                                             <hr>
                                              <div class="grid-body no-border">
                                                  <ul>
                                                      <li>Lorem ipsum dolor sit </li>
                                                      <li>Consectetur adipiscing </li>
                                                      <li>Integer molestie lorem at </li>
                                                      <li>Facilisis in pretium nisl </li>
                                                      <li>Nulla volutpat aliquam </li>
                                                  </ul>
                                              </div>
                                              <button class="btn btn-block btn-primary ca-sub" type="button"
                                              data-toggle="modal" data-target="#myModal">Active</button>

                                          </div>
                                        </a>
                                    </li>'

        className : 'ca-menu'

        tagName : 'ul'

    # return the view instances as objects
    PlansListLayout : PlansListLayout
    CurrentPlanView : CurrentPlanView
    PlanListView : PlanListView








