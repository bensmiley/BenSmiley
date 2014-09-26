var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'text!apps/plans/templates/listPlanView.html', 'text!apps/plans/templates/beta-release.html'], function(Marionette, listPlanViewTpl, betaRelease) {
  var BetaReleaseView, PlansListView, SinglePlanView;
  SinglePlanView = (function(_super) {
    __extends(SinglePlanView, _super);

    function SinglePlanView() {
      return SinglePlanView.__super__.constructor.apply(this, arguments);
    }

    SinglePlanView.prototype.template = '<div class="text-center"> <h4 class="title1">{{plan_name}}</h4> <div class="content1" > <div class="price"><sup>&#36;</sup><span>{{price}}</span><sub>/month</sub> </div> </div> {{{description}}} <div class="pt-footer"> <a href="#change-plan/{{plan_id}}" class="plan-link">Subscribe</a> </div> </div>';

    SinglePlanView.prototype.tagName = 'li';

    SinglePlanView.prototype.className = 'plans';

    SinglePlanView.prototype.onShow = function() {
      var activePlanName, domainId, linkValue, newLinkValue, planName;
      linkValue = this.$el.find('.plan-link').attr('href');
      domainId = Marionette.getOption(this, 'domainId');
      newLinkValue = "" + linkValue + "/" + domainId;
      this.$el.find('.plan-link').attr({
        'href': newLinkValue
      });
      activePlanName = Marionette.getOption(this, 'activePlanName');
      planName = this.model.get('plan_name');
      if (activePlanName === planName) {
        this.$el.addClass('highlight');
        return this.$el.find('.plan-link').attr({
          'href': 'javascript:void(0)'
        });
      }
    };

    return SinglePlanView;

  })(Marionette.ItemView);
  PlansListView = (function(_super) {
    __extends(PlansListView, _super);

    function PlansListView() {
      return PlansListView.__super__.constructor.apply(this, arguments);
    }

    PlansListView.prototype.template = listPlanViewTpl;

    PlansListView.prototype.itemViewContainer = '.ca-menu';

    PlansListView.prototype.itemView = SinglePlanView;

    PlansListView.prototype.serializeData = function() {
      var data;
      data = PlansListView.__super__.serializeData.call(this);
      data.plan_name = (this.model.get('active_subscription')).plan_name;
      data.start_date = (this.model.get('active_subscription')).start_date;
      return data;
    };

    PlansListView.prototype.itemViewOptions = function() {
      var domainID, planName;
      planName = (this.model.get('active_subscription')).plan_name;
      domainID = Marionette.getOption(this, 'domainId');
      return {
        activePlanName: planName,
        domainId: domainID
      };
    };

    return PlansListView;

  })(Marionette.CompositeView);
  BetaReleaseView = (function(_super) {
    __extends(BetaReleaseView, _super);

    function BetaReleaseView() {
      return BetaReleaseView.__super__.constructor.apply(this, arguments);
    }

    BetaReleaseView.prototype.template = betaRelease;

    BetaReleaseView.prototype.initialize = function(opts) {
      return this.domainId = opts.domainID;
    };

    BetaReleaseView.prototype.mixinTemplateHelpers = function(data) {
      if (data == null) {
        data = {};
      }
      data.domain_id = this.domainId;
      return data;
    };

    return BetaReleaseView;

  })(Marionette.ItemView);
  return {
    PlansListView: PlansListView,
    BetaReleaseView: BetaReleaseView
  };
});
