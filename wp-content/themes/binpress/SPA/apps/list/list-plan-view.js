(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['marionette', 'text!apps/plans/templates/listPlanView.html'], function(Marionette, listPlanViewTpl) {
    var PlansListView, SinglePlanView;
    SinglePlanView = (function(_super) {
      __extends(SinglePlanView, _super);

      function SinglePlanView() {
        return SinglePlanView.__super__.constructor.apply(this, arguments);
      }

      SinglePlanView.prototype.template = '<div> <div class="grid simple"> <h4 class="bold text-center plan-name">{{name}}<br> <small class="text-danger" >Rs.{{price}}/month</small> </h4> <hr> <div class="grid-body no-border"> <ul> <li>{{description}} </li> </ul> </div> </div> <a href="#change-plan/{{plan_id}}" class="btn btn-block btn-primary ca-sub plan-link">Subscribe</a> </div>';

      SinglePlanView.prototype.tagName = 'li';

      SinglePlanView.prototype.className = 'plans';

      SinglePlanView.prototype.onShow = function() {
        var activePlanName, domainId, linkValue, newLinkValue, planName;
        activePlanName = Marionette.getOption(this, 'activePlanName');
        planName = this.model.get('name');
        if (activePlanName === planName) {
          this.$el.addClass('highlight');
        }
        linkValue = this.$el.find('.plan-link').attr('href');
        domainId = Marionette.getOption(this, 'domainId');
        newLinkValue = "" + linkValue + "/" + domainId;
        return this.$el.find('.plan-link').attr({
          'href': newLinkValue
        });
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

      PlansListView.prototype.itemViewOptions = function() {
        return {
          activePlanName: this.model.get('name'),
          domainId: this.model.get('domain_id')
        };
      };

      return PlansListView;

    })(Marionette.CompositeView);
    return PlansListView;
  });

}).call(this);
