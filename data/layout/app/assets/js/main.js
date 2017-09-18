$(document).foundation();


$(function()
{
	$(".fancybox").fancybox({
    helpers : {
      overlay : {
        css : {
          'background' 	: 'rgba(255, 255, 255, 0.35)'
        }
      }
    }
  });

  $(".fancybox-list-brands").fancybox({
  	helpers : {
      overlay : {
        css : {
          'background' 	: 'rgba(0, 0, 0, 0.5)'
        }
      }
    },
    'padding': 2
  });


  $('.jsc').jscroll({
		autoTrigger: false,
		nextSelector: 'a.next'
	});	
})

angular.element(document).ready(function(){
  angular.bootstrap(document, ['app']);
});
