(function() {
  'use strict';
  angular.module('app.directives', []).directive('imgHolder', [
    function() {
      return {
        restrict: 'A',
        link: function(scope, ele, attrs) {
          return Holder.run({
            images: ele[0]
          });
        }
      };
    }
  ]).directive('customPage', function() {
    return {
      restrict: "A",
      controller: [
        '$scope', '$element', '$location', function($scope, $element, $location) {
          var addBg, path;
          path = function() {
            return $location.path();
          };
          addBg = function(path) {
            $element.removeClass('body-wide body-lock');
            switch (path) {
              case '/404':
              case '/pages/404':
              case '/pages/500':
              case '/pages/signin':
              case '/pages/signup':
              case '/pages/forgot-password':
                return $element.addClass('body-wide');
              case '/pages/lock-screen':
                return $element.addClass('body-wide body-lock');
            }
          };
          addBg($location.path());
          return $scope.$watch(path, function(newVal, oldVal) {
            if (newVal === oldVal) {
              return;
            }
            return addBg($location.path());
          });
        }
      ]
    };
  }).directive('uiColorSwitch', [
    function() {
      return {
        restrict: 'A',
        link: function(scope, ele, attrs) {
          return ele.find('.color-option').on('click', function(event) {
            var $this, hrefUrl, style;
            $this = $(this);
            hrefUrl = void 0;
            style = $this.data('style');
            if (style === 'loulou') {
              hrefUrl = 'styles/main.css';
              $('link[href^="styles/main"]').attr('href', hrefUrl);
            } else if (style) {
              style = '-' + style;
              hrefUrl = 'styles/main' + style + '.css';
              $('link[href^="styles/main"]').attr('href', hrefUrl);
            } else {
              return false;
            }
            return event.preventDefault();
          });
        }
      };
    }
  ]).directive('goBack', [
    function() {
      return {
        restrict: "A",
        controller: [
          '$scope', '$element', '$window', function($scope, $element, $window) {
            return $element.on('click', function() {
              return $window.history.back();
            });
          }
        ]
      };
    }
  ])
  
  //Moeda
    .directive("ngMoeda", ['$filter', function($filter){

        return {
            restrict: 'A',
            require: ['?ngModel'],
            scope: {
                valor: '=ngModel'
            },
            link: function(scope, element, attrs, ctrl){

                if(attrs['ngMoeda'] === "true" || attrs['ngMoeda'] === true){

                    var qtdD    = attrs.digitosDecimal || 2

                    element.context.value = element.context.value || $filter('moeda')(0, attrs.simbolo, attrs.separadorDecimal, attrs.separadorMilhar, qtdD);

                    if(angular.isDefined(ctrl[0]))
                        scope.$watch('valor', change);
                    else
                        element.on('keyup', change);

                }

            }
        };

    }]);

    function change(){

        var value   = element.context.value,
            newV    = 0,
            qtdD    = qtdD || 2;

        if(Number(value).toString() !== "NaN")
            value = Number(value).toFixed(qtdD);

        value   = value ? value.replace(/[^(0-9).]/g, "").replace(/\./g, "") : '';

        if(String(value).length <= qtdD){
            while(String(value).length < qtdD)
                value = "0"+value;
            value = "0."+value;
        }else{
            value       = value+'!';
            decimals    = value.slice((qtdD+1)*-1);
            value       = value.replace(decimals, "."+decimals.slice(0, decimals.length-1));
        }

        value = Number(value);
        newV = $filter('moeda')(value, attrs.simbolo, attrs.separadorDecimal, attrs.separadorMilhar, qtdD);

        element.context.value = newV;
        if(angular.isDefined(ctrl[0]))
            ctrl[0].$setViewValue(value);

    }
    //

}).call(this);
