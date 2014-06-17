(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['marionette'], function(Marionette) {
    var AddGroupView;
    AddGroupView = (function(_super) {
      __extends(AddGroupView, _super);

      function AddGroupView() {
        return AddGroupView.__super__.constructor.apply(this, arguments);
      }

      AddGroupView.prototype.tagName = 'form';

      AddGroupView.prototype.template = ' <div class="row"> <div class="col-md-12"> <div class="form-group"> <label class="form-label">Group Name</label> <span class="help">e.g. "xyz"</span> <div class="input-with-icon  right"> <i class=""></i> <input type="text" name="group_name" id="group_name" class="form-control"> </div> </div> <div class="form-group"> <label class="form-label">Description</label> <div class="input-with-icon  right"> <i class=""></i> <textarea type="text" name="group_description" id="group_description" class="form-control"> </textarea> </div> </div> <div class="pull-right"> <button type="button" class="btn btn-primary btn-cons" id="btn-save-domain-group"> <i class="icon-ok"></i> Save</button> <input type="reset" style="display: none" id="btn-reset-group"/> </div> </div> </div>';

      AddGroupView.prototype.events = {
        'click #btn-save-domain-group': function() {
          var groupdata;
          if (this.$el.valid()) {
            groupdata = Backbone.Syphon.serialize(this);
            groupdata.domain_id = Marionette.getOption(this, 'domain_id');
            return this.trigger("save:domain:group:clicked", groupdata);
          }
        }
      };

      AddGroupView.prototype.onShow = function() {
        return this.$el.validate(this.validationOptions());
      };

      AddGroupView.prototype.onDomainGroupAdded = function() {
        return this.$el.find('#btn-reset-group').click();
      };

      AddGroupView.prototype.validationOptions = function() {
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

      return AddGroupView;

    })(Marionette.ItemView);
    return AddGroupView;
  });

}).call(this);
