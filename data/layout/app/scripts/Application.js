var application = angular.module("Application", [
    'ui.bootstrap',
    'blockUI',
    'ngSanitize'
  ])
  .config(['blockUIConfig', '$provide', function(blockUIConfig, $provide) {

    blockUIConfig.message = "Carregando Informações...";
    blockUIConfig.autoBlock = false;

    $provide.decorator('alertDirective', ['$delegate', function($delegate) {
      angular.extend($delegate[0].scope, {
        icon: '@'
      });
      return $delegate;
    }]);

  }])
  .run(['$rootScope', 'EVENTS', 'blockUI', function($rootScope, EVENTS, blockUI) {

    var block = blockUI.instances.get('app');

    $rootScope.$on(EVENTS.block, function() {
      block.start();
    });
    $rootScope.$on(EVENTS.unblock, function() {
      block.stop();
    });

    $rootScope.$on(EVENTS.wait, function() {
      $rootScope.$emit(EVENTS.block);
    });
    $rootScope.$on(EVENTS.ready, function() {
      $rootScope.$emit(EVENTS.unblock);
    });
  }]);

/*
 * Constantes
 */
application
  .constant("EVENTS", {
    block: "block",
    unblock: "unblock",
    wait: "wait",
    ready: "ready"
  });