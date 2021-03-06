var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['marionette', 'mustache', 'spin', 'jqueryspin'], function(Marionette, Mustache, spin, jqueryspin) {
  _.extend(Marionette.Application.prototype, {
    navigate: function(route, options) {
      if (options == null) {
        options = {};
      }
      return Backbone.history.navigate(route, options);
    },
    getCurrentRoute: function() {
      var frag;
      frag = Backbone.history.fragment;
      if (_.isEmpty(frag)) {
        return null;
      } else {
        return frag;
      }
    },
    startHistory: function() {
      if (Backbone.history) {
        return Backbone.history.start();
      }
    },
    register: function(instance, id) {
      if (this._registry == null) {
        this._registry = {};
      }
      return this._registry[id] = instance;
    },
    unregister: function(instance, id) {
      return delete this._registry[id];
    },
    resetRegistry: function() {
      var controller, key, msg, oldCount, _ref;
      oldCount = this.getRegistrySize();
      _ref = this._registry;
      for (key in _ref) {
        controller = _ref[key];
        controller.region.close();
      }
      msg = "There were " + oldCount + " controllers in the registry, there are now " + (this.getRegistrySize());
      if (this.getRegistrySize() > 0) {
        return console.warn(msg, this._registry);
      } else {
        return console.log(msg);
      }
    },
    getRegistrySize: function() {
      return _.size(this._registry);
    },
    registerElement: function(instance, id) {
      if (this._elementRegistry == null) {
        this._elementRegistry = {};
      }
      return this._elementRegistry[id] = instance;
    },
    unregisterElement: function(instance, id) {
      return delete this._elementRegistry[id];
    },
    resetElementRegistry: function() {
      var controller, key, msg, oldCount, _ref;
      oldCount = this.getElementRegistrySize();
      _ref = this._elementRegistry;
      for (key in _ref) {
        controller = _ref[key];
        controller.layout.close();
      }
      msg = "There were " + oldCount + " controllers in the registry, there are now " + (this.getElementRegistrySize());
      if (this.getElementRegistrySize() > 0) {
        return console.warn(msg, this._elementRegistry);
      } else {
        return console.log(msg);
      }
    },
    getElementRegistrySize: function() {
      return _.size(this._elementRegistry);
    }
  });
  _.extend(Marionette.Region.prototype, {
    hide: function() {
      return this.$el.hide();
    },
    unhide: function() {
      return this.$el.show();
    }
  });
  Marionette.Renderer.render = function(template, data) {
    if (!template) {
      template = '';
    }
    if (typeof template === "function") {
      template = template();
    }
    return Mustache.to_html(template, data);
  };
  Marionette.TemplateCache.prototype.loadTemplate = function(templateId) {
    var err, msg, template;
    template = templateId;
    if (!template || template.length === 0) {
      msg = "Could not find template: '" + templateId + "'";
      err = new Error(msg);
      err.name = "NoTemplateError";
      throw err;
    }
    return template;
  };
  return Marionette.LoadingView = (function(_super) {
    __extends(LoadingView, _super);

    function LoadingView() {
      return LoadingView.__super__.constructor.apply(this, arguments);
    }

    LoadingView.prototype.template = _.template('<i></i>', {});

    LoadingView.prototype.className = 'loading-container';

    LoadingView.prototype.onShow = function() {
      var opts;
      opts = this._getOptions();
      return this.$el.spin(opts);
    };

    LoadingView.prototype.onClose = function() {
      return this.$el.spin(false);
    };

    LoadingView.prototype._getOptions = function() {
      return {
        lines: 10,
        length: 6,
        width: 2.5,
        radius: 7,
        corners: 1,
        rotate: 9,
        direction: 1,
        color: '#000000',
        speed: 1,
        trail: 60,
        shadow: false,
        hwaccel: true,
        className: 'spinner',
        zIndex: 1030,
        top: '40%',
        left: '50%'
      };
    };

    return LoadingView;

  })(Marionette.ItemView);
});
