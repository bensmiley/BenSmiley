(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['marionette'], function(Marionette) {
    var ActiveSubscriptionView, PlanListsView, PlansListLayout, SinglePlanView;
    PlansListLayout = (function(_super) {
      __extends(PlansListLayout, _super);

      function PlansListLayout() {
        return PlansListLayout.__super__.constructor.apply(this, arguments);
      }

      PlansListLayout.prototype.template = '<!-- TABS --> <ul class="nav nav-tabs" id="tab-01"> <li><a href="#domains">Domain Details</a></li> <li class="active"><a href="javascript:void(0)">Domain Plan</a></li> <li><a href="#">Statistics</a></li> </ul> <div class="tab-content"> <!-- Show user domain and add new user domain region --> <div class="tab-pane active"> <div class="page-title"><i class="icon-custom-left"></i> <h3>Pricing <span class="semi-bold"> and Plans</span></h3> </div> <div id="active-subscription"></div> <br> <div id="plans-list"></div> <div class="clearfix"></div> </div> <hr> </div>';

      PlansListLayout.prototype.regions = {
        activeSubscriptionRegion: '#active-subscription',
        plansListRegion: '#plans-list'
      };

      PlansListLayout.prototype.onShow = function() {
        var activePlan;
        activePlan = this.$el.find('#active-plan-name').text();
        return console.log(activePlan);
      };

      return PlansListLayout;

    })(Marionette.Layout);
    ActiveSubscriptionView = (function(_super) {
      __extends(ActiveSubscriptionView, _super);

      function ActiveSubscriptionView() {
        return ActiveSubscriptionView.__super__.constructor.apply(this, arguments);
      }

      ActiveSubscriptionView.prototype.template = ' <div class="col-md-12"> <div class="tiles blue"> <div class="row"> <div class="col-md-3"> <div class="tiles-body"> <div > ACTIVE PLAN </div> <div class="heading"> <span class="animate-number" id="active-plan-name">{{name}}</span> </div> </div> </div> <div class="col-md-3"> <div class="tiles-body"> <div > ACTIVE SINCE </div> <div class="heading"> <span class="animate-number" >{{start_date}}</span> </div> </div> </div> </div> </div> </div>';

      ActiveSubscriptionView.prototype.className = 'row';

      return ActiveSubscriptionView;

    })(Marionette.ItemView);
    SinglePlanView = (function(_super) {
      __extends(SinglePlanView, _super);

      function SinglePlanView() {
        return SinglePlanView.__super__.constructor.apply(this, arguments);
      }

      SinglePlanView.prototype.template = '<div> <div class="grid simple"> <h4 class="bold text-center plan-name">{{name}}<br> <small class="text-danger" >Rs.{{price}}/month</small> </h4> <hr> <div class="grid-body no-border"> <ul> <li>{{description}} </li> </ul> </div> </div> <a href="#change-plan/{{plan_id}}" class="btn btn-block btn-primary ca-sub plan-link">Subscribe</a> </div>';

      SinglePlanView.prototype.tagName = 'li';

      SinglePlanView.prototype.className = 'plans';

      SinglePlanView.prototype.onShow = function() {
        var a;
        a = Marionette.getOption(this, 'domainId');
        return console.log(a);
      };

      return SinglePlanView;

    })(Marionette.ItemView);
    PlanListsView = (function(_super) {
      __extends(PlanListsView, _super);

      function PlanListsView() {
        return PlanListsView.__super__.constructor.apply(this, arguments);
      }

      PlanListsView.prototype.template = ' <ul class="ca-menu"></ul> ';

      PlanListsView.prototype.itemViewContainer = '.ca-menu';

      PlanListsView.prototype.itemView = SinglePlanView;

      PlanListsView.prototype.onShow = function() {
        var domainId;
        domainId = Marionette.getOption(this, 'domainId');
        return console.log(a);
      };

      return PlanListsView;

    })(Marionette.CompositeView);
    return {
      PlansListLayout: PlansListLayout,
      ActiveSubscriptionView: ActiveSubscriptionView,
      PlanListsView: PlanListsView
    };
  });

}).call(this);
