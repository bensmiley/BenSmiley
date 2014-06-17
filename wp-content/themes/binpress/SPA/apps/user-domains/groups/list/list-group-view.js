(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['marionette'], function(Marionette) {
    var ListGroupView;
    ListGroupView = (function(_super) {
      __extends(ListGroupView, _super);

      function ListGroupView() {
        return ListGroupView.__super__.constructor.apply(this, arguments);
      }

      ListGroupView.prototype.template = '<table class="table table-hover table-condensed" id="example"> <thead> <tr> <th style="width:15%" >Group Name</th> <th style="width:30%" data-hide="phone,tablet">Description</th> <th style="width:10%">Action</th> </tr> </thead> <tbody> <tr > <td class="v-align-middle"><span class="muted">Group 1</span></td> <td><span class="muted">frequently involving research or design</span></td> <td class="v-align-middle"> <span class="glyphicon glyphicon-pencil"></span>  &nbsp; <span class="glyphicon glyphicon-trash"></span> </td> </tr> <tr> <td><span class="muted">Group 2</span></td> <td><span class="muted">Something goes here</span></td> <td> <span class="glyphicon glyphicon-pencil"></span>  &nbsp; <span class="glyphicon glyphicon-trash"></span> </td> </tr> <tr> <td class="v-align-middle"><span class="muted">Group 3</span></td> <td><span class="muted">Redesign project template</span></td> <td> <span class="glyphicon glyphicon-pencil"></span>  &nbsp; <span class="glyphicon glyphicon-trash"></span> </td> </tr> <tr> <td class="v-align-middle"><span class="muted">Group 4</span></td> <td><span class="muted">A project in business and science is typically defined</span></td> <td> <span class="glyphicon glyphicon-pencil"></span>  &nbsp; <span class="glyphicon glyphicon-trash"></span> </td> </tr> </tbody> </table>';

      ListGroupView.prototype.events = {
        'click #btn-save-domain-group': function() {
          var groupdata;
          if (this.$el.valid()) {
            groupdata = Backbone.Syphon.serialize(this);
            groupdata.domain_id = Marionette.getOption(this, 'domain_id');
            return this.trigger("save:domain:group:clicked", groupdata);
          }
        }
      };

      ListGroupView.prototype.onShow = function() {
        return this.$el.validate(this.validationOptions());
      };

      ListGroupView.prototype.onDomainGroupAdded = function() {
        return this.$el.find('#btn-reset-group').click();
      };

      ListGroupView.prototype.validationOptions = function() {
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

      return ListGroupView;

    })(Marionette.ItemView);
    return ListGroupView;
  });

}).call(this);
