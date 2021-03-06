define("plugins-loader", ['underscore', 'jquery', 'bootstrap', 'backbone', 'marionette', 'backbonesyphon', 'additionalmethods', 'jqueryvalidate'], function() {});

define("config-loader", ['configs/backbone.config', 'configs/marionette.config', 'configs/jquery.config'], function() {});

define("apps-loader", ['apps/leftnav/leftnav-app', 'apps/header/header-app', 'apps/upload/upload-controller', 'apps/user-profile/user-profile-app', 'apps/user-domains/user-domains-app', 'apps/plans/plans-app', 'apps/payment/payment-app']);

define("entitites-loader", ['entities/user', 'entities/domains', 'entities/groups', 'entities/plans', 'entities/subscription']);

define("app", ['pages/dashboard.app'], function(App) {
  return App;
});

require(['plugins-loader', 'config-loader', 'app', 'entitites-loader', 'apps-loader', 'loading/controller'], function(p, c, App) {
  return App.start();
});
