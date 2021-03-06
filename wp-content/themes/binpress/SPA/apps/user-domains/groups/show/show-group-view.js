var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

define(['marionette', 'text!apps/user-domains/templates/groupsTemplate.html'], function(Marionette, groupsTpl) {
  var EmptyGroupView, ShowGroupView, SingleGroupView;
  SingleGroupView = (function(_super) {
    __extends(SingleGroupView, _super);

    function SingleGroupView() {
      return SingleGroupView.__super__.constructor.apply(this, arguments);
    }

    SingleGroupView.prototype.template = '<td class="v-align-middle"><span class="muted">{{group_name}}</span></td> <td><span class="muted">{{group_description}}</span></td>';

    SingleGroupView.prototype.tagName = 'tr';

    SingleGroupView.prototype.events = {
      'click': function() {
        return this.trigger("edit:group:clicked", this.model);
      }
    };

    SingleGroupView.prototype.modelEvents = {
      'change': 'render'
    };

    return SingleGroupView;

  })(Marionette.ItemView);
  EmptyGroupView = (function(_super) {
    __extends(EmptyGroupView, _super);

    function EmptyGroupView() {
      return EmptyGroupView.__super__.constructor.apply(this, arguments);
    }

    EmptyGroupView.prototype.template = '<td class="v-align-middle nocol" colspan="3" align="center"> <span class="muted">No Groups added</span></td> </td>';

    EmptyGroupView.prototype.tagName = 'tr';

    return EmptyGroupView;

  })(Marionette.ItemView);
  ShowGroupView = (function(_super) {
    __extends(ShowGroupView, _super);

    function ShowGroupView() {
      this.onGroupUpdated = __bind(this.onGroupUpdated, this);
      this.onDomainGroupAdded = __bind(this.onDomainGroupAdded, this);
      return ShowGroupView.__super__.constructor.apply(this, arguments);
    }

    ShowGroupView.prototype.template = groupsTpl;

    ShowGroupView.prototype.itemViewContainer = 'tbody';

    ShowGroupView.prototype.itemView = SingleGroupView;

    ShowGroupView.prototype.emptyView = EmptyGroupView;

    ShowGroupView.prototype.initialize = function() {
      return this.listenTo(this, "itemview:edit:group:clicked", this.editGroup);
    };

    ShowGroupView.prototype.editGroup = function(itemview, model) {
      var group_description, group_name;
      this.editModel = model;
      group_name = model.get('group_name');
      group_description = model.get('group_description');
      this.$el.find('#btn-new-ticket').click();
      this.$el.find('#btn-save-domain-group').text('Update');
      this.$el.find('.delete-group').show();
      this.$el.find('#group_name').val(group_name);
      return this.$el.find('#group_description').val(group_description);
    };

    ShowGroupView.prototype.events = {
      'click #btn-save-domain-group': function() {
        var buttonText, formAction, groupdata;
        if (this.$el.find('#add-group-form').valid()) {
          groupdata = Backbone.Syphon.serialize(this);
          groupdata.domain_id = Marionette.getOption(this, 'domainId');
          buttonText = this.$el.find('#btn-save-domain-group').text();
          formAction = buttonText.trim();
          if (formAction === "Save") {
            return this.trigger("save:domain:group:clicked", groupdata);
          } else {
            return this.trigger("update:domain:group:clicked", groupdata, this.editModel);
          }
        }
      },
      'click #btn-new-ticket': function() {
        this.$el.find('#success-msg').empty();
        this.$el.find('#new-ticket-wrapper').slideToggle("fast", "linear");
        this.$el.find('#btn-new-ticket').css({
          'display': 'none'
        });
        this.$el.find('#group_name').val(' ');
        this.$el.find('#group_description').val(' ');
        this.$el.find('#btn-save-domain-group').text('Save');
        return this.$el.find('.delete-group').hide();
      },
      'click #btn-close-ticket': function() {
        this.$el.find('#new-ticket-wrapper').slideToggle("fast", "linear");
        return this.$el.find('#btn-new-ticket').css({
          'display': 'inline'
        });
      },
      'click .delete-group': function() {
        if (confirm('Are you sure?')) {
          return this.trigger("delete:group:clicked", this.editModel);
        }
      }
    };

    ShowGroupView.prototype.onShow = function() {
      return this.$el.find('#add-group-form').validate(this.validationOptions());
    };

    ShowGroupView.prototype.validationOptions = function() {
      return {
        rules: {
          group_name: {
            required: true
          },
          group_description: {
            required: true
          }
        },
        messages: {
          group_name: 'Enter valid group name'
        }
      };
    };

    ShowGroupView.prototype.onDomainGroupAdded = function() {
      var msg;
      msg = "Group added for domain sucessfully";
      this.showSuccessMsg(msg);
      return this.$el.find('#btn-reset-group').click();
    };

    ShowGroupView.prototype.onGroupUpdated = function() {
      var msg;
      msg = "Group updated sucessfully";
      return this.showSuccessMsg(msg);
    };

    ShowGroupView.prototype.onGroupDeleted = function() {
      return this.$el.find('#btn-close-ticket').click();
    };

    ShowGroupView.prototype.showSuccessMsg = function(msgText) {
      var msg;
      this.$el.find('#success-msg').empty();
      msg = "<div class='alert alert-success'> <button class='close' data-dismiss='alert'></button> " + msgText + "<div>";
      return this.$el.find('#success-msg').append(msg);
    };

    return ShowGroupView;

  })(Marionette.CompositeView);
  return ShowGroupView;
});
