(function() {
	'use strict';
	angular.module('app', ['Application', 'ui.router', 'ngRoute', 'ngAnimate']).config([
			'$stateProvider', '$locationProvider', '$urlRouterProvider',
			function($stateProvider, $locationProvider, $urlRouterProvider) {

				$locationProvider.hashPrefix("!");
				$locationProvider.html5Mode(true);
				$urlRouterProvider.otherwise('/');

				var routes = {
					'home': {
						name: 'home',
						url: '/',
						views: {
							"": {
								templateUrl: "views/main.html",
								controller: "HomeController"
							}
						}
					},
					'category': {
						name: 'category',
						url: '/c/:idcategoria',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "CategoriaController"
							}
						}
					},
					'category-page': {
						name: 'category-page',
						url: '/c/:idcategoria/:page',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "CategoriaController"
							}
						}
					},
					'category-brand': {
						name: 'category-brand',
						url: '/c/:idcategoria/m/:idmarca',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "CategoriaController"
							}
						}
					},
					'category-brand-page': {
						name: 'category-brand-page',
						url: '/c/:idcategoria/m/:idmarca/:page',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "CategoriaController"
							}
						}
					},
					'brand': {
						name: 'brand',
						url: '/m/:idmarca',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "MarcaController"
							}
						}
					},
					'brand-page': {
						name: 'brand-page',
						url: '/m/:idmarca/:page',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "MarcaController"
							}
						}
					},
					'search': {
						name: 'search',
						url: '/search/:busca?marca',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "BuscaController"
							}
						}
					},
					'search-page': {
						name: 'search-page',
						url: '/search/:busca/:page?marca',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "BuscaController"
							}
						}
					},
					'products': {
						name: 'products',
						url: '/produtos',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "ProdutosController"
							}
						}
					},
					'products-page': {
						name: 'products-page',
						url: '/produtos/:page',
						views: {
							"": {
								templateUrl: "views/product-list.html",
								controller: "ProdutosController"
							}
						}
					},
					'product': {
						name: 'product',
						url: '/c/:idcategoria/p/:idproduto',
						views: {
							"": {
								templateUrl: "views/product.html",
								controller: "ProdutoController"
							}
						}
					},
          'empresa': {
            name: 'empresa',
            url: '/empresa',
            views: {
              "": {
                templateUrl: 'views/simple-page.html',
                controller: 'EmpresaController'
              }
            }
          },
          'novos': {
            name: 'novos',
            url: '/novos',
            views: {
              "": {
                templateUrl: 'views/news-products.html',
                controller: 'NovosController'
              }
            }
          },
					'sellers': {
						name: 'sellers',
						url: '/vendedores',
						views: {
							"": {
								templateUrl: "views/sellers.html",
								controller: "VendedoresController"
							}
						}
					},
          'brands': {
            name: 'brands',
            url: '/marcas',
            views: {
              "": {
                templateUrl: "views/brands.html",
                controller: "MarcasController"
              }
            }
          },
          'assistencia': {
            name: 'assistencia',
            url: '/assistencia',
            views: {
              "": {
                templateUrl: 'views/simple-page.html',
                controller: 'AssistenciaController'
              }
            }
          },
          'contato': {
            name: 'contato',
            url: '/contato',
            views: {
              "": {
                templateUrl: 'views/contact.html',
                controller: 'ContatoController'
              }
            }
          },
					'price-list': {
						name: 'price-list',
						url: '/lista-de-precos',
						views: {
							"": {
								templateUrl: "views/price-list.html",
								controller: "ListaPrecosController"
							}
						}
					},
					'location': {
						name: 'location',
						url: '/localizacao',
						views: {
							"": {
								templateUrl: "views/location.html",
								controller: "LocalizacaoController"
							}
						}
					},
					'currency-exchange': {
						name: 'currency-exchange',
						url: '/casas-de-cambio',
						views: {
							"": {
								templateUrl: "views/currency-exchange.html",
								controller: "CasasCambioController"
							}
						}
					}
				};

				function registerRoutes(route, state) {
					for (var k in route) {
						var r = route[k],
							children = r.childrenRoutes;

						delete r.childrenRoutes;

						registerRoutes(children, state.state(r));

					}
				}

				registerRoutes(routes, $stateProvider);

			}
		])
		.run(['$rootScope', '$state', '$document', 'blockUI', '$window', '$location', '$interval', '$http', '$filter', function($rootScope, $state, $document, blockUI, $window, $location, $interval, $http, $filter) {

			$rootScope.frontURL 						= document.location.origin + "/";
      $rootScope.backURL        	 	  = $rootScope.frontURL;
      //$rootScope.backURL        			= 'http://10.211.55.39/';
			//$rootScope.backURL          		= 'https://brasilianas-jhonnybail.c9users.io/';
			//$rootScope.backURL							= 'https://grupobraziliana.com.br/';
			$rootScope.productImagesURL 		= $rootScope.backURL + "/lib.data/produtos/";
			$rootScope.productCategoriesURL = $rootScope.backURL + "/lib.data/produtos/categorias/";
			$rootScope.productBrandsURL 		= $rootScope.backURL + "/lib.data/produtos/marcas/";
			$rootScope.slidesURL 						= $rootScope.backURL + "/lib.data/utilidades/publicidades/slides/";
			$rootScope.bannersURL 					= $rootScope.backURL + "/lib.data/utilidades/publicidades/banners/";
			
			$rootScope.search = {
				load: false
			};
			$rootScope.newsletter = {
				typeMessage: 'info',
				message: ''
			};
      $rootScope.lancamentos = [];
			
			$http.get($rootScope.backURL + 'application/site/general').then(function(data) {

				$rootScope.cambio 						= data.data.cambio;
				$rootScope.produtosPorPagina	= data.data.produtosporpagina;
				$rootScope.slides 						= data.data.slides;
        $rootScope.categorias					= data.data.categorias;
        $rootScope.marcas							= data.data.marcas;
        $rootScope.lancamentos				= data.data.lancamentos;

				$.each($rootScope.categorias, function(k, v){
					if(v.imagem != '')
						v.imagem = $rootScope.productCategoriesURL + $filter('resizeImage')(v.imagem, 50, 50);
				});
				$.each($rootScope.marcas, function(k, v){
					if(v.imagem != '')
						v.imagem = $rootScope.productBrandsURL + $filter('resizeImage')(v.imagem, 153, 153);
				});
				$.each($rootScope.lancamentos, function(k, v){
					$rootScope.tratarProduto(v);
				});
				$.each($rootScope.slides, function(k, v){
					v.imagem = $rootScope.slidesURL + v.imagem;
				});

			}, function(error) {});

			$rootScope.$state = $state;
			$rootScope.$on('$stateChangeStart', function(evt, toState) {

			});
			$rootScope.$on('$stateChangeSuccess', function(evt, toState) {
				//ga('send', 'pageview', {
				//	'page': $location.path(),
				//	'title': toState.name
				//});
			});
			
			$rootScope.tratarProduto = function(produto){
				
				if(produto.categorias.length > 0){
					produto.urlcategoria = produto.categorias[0].id + '-' + produto.categorias[0].url.url;
					produto.urlproduto   = produto.id + '-' + produto.url.url;
					produto.link = '/c/' + produto.urlcategoria + '/p/' + produto.urlproduto;
				}
				//Imagens
				if(produto.imagens && produto.imagens.length > 0){
					$.each(produto.imagens, function(k2, imagem){
						imagem.imagem = {
							thumb: $rootScope.productImagesURL + $filter('resizeImage')(imagem.imagem, 160, 105),
							medium: $rootScope.productImagesURL + $filter('resizeImage')(imagem.imagem, 500, 500),
							real: $rootScope.productImagesURL + imagem.imagem
						};
					});
				}else if(produto.imagens && produto.imagens.destaque){
					$.each(produto.imagens, function(k2, imagem){
						imagem.imagem = {
							thumb: $rootScope.productImagesURL + $filter('resizeImage')(imagem.imagem, 160, 105),
							medium: $rootScope.productImagesURL + $filter('resizeImage')(imagem.imagem, 500, 500),
							real: $rootScope.productImagesURL + imagem.imagem
						};
					});
				}else{
					produto.imagens = [{
						imagem: {
							thumb: $rootScope.frontURL + 'assets/images/no-image.gif'
						}
					}]
				}
				//
				
				//Preços
				if(produto.valorreal > 0 && produto.disponivel){
					produto.amostra = $filter('moeda')(produto.valorreal, 'U$ ');
				}else if(produto.valorreal > 0 && produto.disponivel){
					produto.amostra = 'Dispoível';
				}else if(produto.valorreal <= 0 && produto.disponivel){
					produto.amostra = 'Consulte-nos';
				}else if(!produto.disponivel){
					produto.amostra = 'Indispoível';
				}else
					produto.amostra = '';
				//
			}

			$rootScope.setProduto = function(produto) {
				$rootScope.produto = produto;
			};

			$rootScope.addNewsletter = function(){

				if($('#email-news').val() === ''){
					$rootScope.newsletter.typeMessage = 'info';
					$rootScope.newsletter.message = 'Por favor, digite seu e-mail acima!';
					return false;
				}

				blockUI.start();

				$http.post($rootScope.backURL + 'application/site/add-newsletter', "email="+$('#email-news').val(), {
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				}).then(function(result){
					blockUI.stop();
					if(result.data.success){
						$rootScope.newsletter.typeMessage = 'success';
						$rootScope.newsletter.message = result.data.message;
					}else{
						$rootScope.newsletter.typeMessage = 'danger';
						$rootScope.newsletter.message = result.data.message;
					}
				}, function(error){
					blockUI.stop();
					$rootScope.newsletter.typeMessage = 'danger';
					$rootScope.newsletter.message = 'Erro ao cadastrar e-mail, tente mais tarde.';
				});

			};

		}])

	.controller('HomeController', ['$scope', '$rootScope', '$http', '$timeout', 'blockUI', function($scope, $rootScope, $http, $timeout, blockUI) {

		var blockMain = blockUI;

		$scope.produtos = {};

		blockMain.start();
		if (!$rootScope.home) {
			$http.get($scope.backURL + 'application/site/home').then(function(data) {

				$scope.destaques					= data.data['destaques'];
				$scope.lancamentos				= data.data['lancamentos'];
				$scope.gruposDestaques		= [];
				
				$.each($scope.destaques, function(k, produto){
					$rootScope.tratarProduto(produto);
				});
				$.each($scope.lancamentos, function(k, produto){
					$rootScope.tratarProduto(produto);
				});
				
				while($scope.destaques.length > 0){
					$scope.gruposDestaques.push($scope.destaques.splice(0, 5));
				}

				$timeout(function() {
					$('.cycle-slideshow').cycle();
					blockMain.stop();
          $("img.lazy").lazyload({
            effect: "fadeIn"
          });
				}, 500);

			}, function(error) {});

		} else {
			$timeout(function() {
				blockMain.stop();
				$("img.lazy").lazyload({
          effect: "fadeIn"
        });
			}, 100);
		}

	}])

	.controller('CategoriaController', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$interval', 'blockUI', function($scope, $rootScope, $http, $state, $timeout, $interval, blockUI) {

		var cat = $state.params.idcategoria.split("-")[0],
				mar = $state.params.idmarca ? $state.params.idmarca.split("-")[0] : null,
				blockMain = blockUI;

		function addBreadcrumb(categoria) {
			if (categoria.categoriapai !== null) {
				addBreadcrumb(categoria.categoriapai);
			}
			$rootScope.search.breadcrumb.push(categoria);
		}

		function makePaginator() {

			var pages = Number($rootScope.search.pagination.total / $rootScope.search.pagination.length),
					paginas = 0,
					iniPag = 0,
					fimPag = 0;
			pages = Number(pages.toFixed(0)) < pages ? Number(pages.toFixed(0)) + 1 : pages;
			$rootScope.search.pagination.totalPages = pages;
			if (pages > $rootScope.search.pagination.viewPages) {
				paginas = parseInt($rootScope.search.pagination.viewPages / 2);
				if ($rootScope.search.pagination.page + paginas > pages) {
					iniPag = pages - $rootScope.search.pagination.viewPages + 1;
					fimPag = pages;
				} else {
					iniPag = (($rootScope.search.pagination.page) - paginas < 1) ? 1 : (parseInt(paginas / 2) !== paginas / 2 ? ($rootScope.search.pagination.page) - (paginas) : ($rootScope.search.pagination.page) - (paginas - 1));
					fimPag = (iniPag + $rootScope.search.pagination.viewPages - 1 > pages) ? pages : iniPag + $rootScope.search.pagination.viewPages - 1;
					iniPag += (($rootScope.search.pagination.page) > fimPag - (paginas) && parseInt(paginas / 2) !== paginas / 2) ? (fimPag - paginas) - ($rootScope.search.pagination.page) : 0;
					iniPag = parseInt(iniPag);
				}
			} else {
				iniPag = 1;
				fimPag = pages;
			}

			$rootScope.search.pagination.pages = [];
			for (var i = iniPag; i <= fimPag; i++)
				$rootScope.search.pagination.pages.push(i);

		}

		$scope.buscar = function(page) {

			page = page || $state.params.page;
			page = Number(page);

			if (page) {
				$rootScope.search.pagination.page = page;
				$rootScope.search.pagination.offset = (page - 1) * $rootScope.search.pagination.length;
			}

			if ($rootScope.search.order == 1)
				$rootScope.search.pagination.order = {
					nome: "asc"
				};
			else if ($rootScope.search.order == 2)
				$rootScope.search.pagination.order = {
					nome: "desc"
				};
			else if ($rootScope.search.order == 3)
				$rootScope.search.pagination.order = {
					valorreal: "desc"
				};
			else if ($rootScope.search.order == 4)
				$rootScope.search.pagination.order = {
					valorreal: "asc"
				};

			$rootScope.search.result = [];
			$rootScope.search.load = false;
			blockMain.start();
			$http.get($scope.backURL + 'api/categorias/' + cat + (mar ? '/marcas/' + mar : '') + '/produtos', {
				params: {
					offset: $rootScope.search.pagination.offset,
					limit: $rootScope.search.pagination.length,
					order: JSON.stringify($rootScope.search.pagination.order)
				}
			}).then(function(data) {
				$rootScope.search.result = data.data.data.result;
				$rootScope.search.pagination.total = data.data.data.total;
				$.each($rootScope.search.result, function(k, v){
					$rootScope.tratarProduto(v);
				})
				$timeout(function() {
					$("img.lazy").lazyload({
						effect: "fadeIn"
					});
					blockMain.stop();
					makePaginator();
					$rootScope.search.load = true;
					$('html, body').animate({
						scrollTop: ($("#main").offset().top - 70)
					}, 500);
				}, 100);
			}, function(error) {});

		};

		if (($rootScope.search.category === undefined || $rootScope.search.category.id != cat) || ($rootScope.search.brand === undefined || $rootScope.search.brand.id != mar)) {
			var idI = $interval(function() {
				if ($rootScope.produtosPorPagina !== undefined) {
					angular.extend($rootScope.search, {
						pagination: {
							offset: 0,
							length: $rootScope.produtosPorPagina,
							total: 0,
							viewPages: 10,
							totalPages: 1,
							order: {
								nome: "asc"
							},
							pages: [],
							page: 1,
							url: "/c/" + $state.params.idcategoria + "/" + ($state.params.idmarca ? "m/" + $state.params.idmarca + "/" : "")
						},
						category: {},
						brand: {},
						term: undefined,
						result: [],
						breadcrumb: [],
						order: "1"
					});

					$http.get($scope.backURL + 'api/categorias/' + cat).then(function(data) {
						addBreadcrumb(data.data.data);
						$rootScope.search.category = data.data.data;
					}, function(error) {});

					if (mar) {
						$http.get($scope.backURL + 'api/marcas/' + mar).then(function(data) {
							$rootScope.search.brand = data.data.data;
						}, function(error) {});
					}
					$interval.cancel(idI);
					$scope.buscar();
				}
			}, 1000);
		} else {
			$timeout(function() {
				$("img.lazy").lazyload({
					effect: "fadeIn"
				});
			}, 100);
		}

	}])

	.controller('MarcaController', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$interval', 'blockUI', function($scope, $rootScope, $http, $state, $timeout, $interval, blockUI) {

		var mar = $state.params.idmarca ? $state.params.idmarca.split("-")[0] : null,
				blockMain = blockUI;

		function makePaginator() {

			var pages = Number($rootScope.search.pagination.total / $rootScope.search.pagination.length),
					paginas = 0,
					iniPag = 0,
					fimPag = 0;
			pages = Number(pages.toFixed(0)) < pages ? Number(pages.toFixed(0)) + 1 : pages;
			$rootScope.search.pagination.totalPages = pages;
			if (pages > $rootScope.search.pagination.viewPages) {
				paginas = parseInt($rootScope.search.pagination.viewPages / 2);
				if ($rootScope.search.pagination.page + paginas > pages) {
					iniPag = pages - $rootScope.search.pagination.viewPages + 1;
					fimPag = pages;
				} else {
					iniPag = (($rootScope.search.pagination.page) - paginas < 1) ? 1 : (parseInt(paginas / 2) !== paginas / 2 ? ($rootScope.search.pagination.page) - (paginas) : ($rootScope.search.pagination.page) - (paginas - 1));
					fimPag = (iniPag + $rootScope.search.pagination.viewPages - 1 > pages) ? pages : iniPag + $rootScope.search.pagination.viewPages - 1;
					iniPag += (($rootScope.search.pagination.page) > fimPag - (paginas) && parseInt(paginas / 2) !== paginas / 2) ? (fimPag - paginas) - ($rootScope.search.pagination.page) : 0;
					iniPag = parseInt(iniPag);
				}
			} else {
				iniPag = 1;
				fimPag = pages;
			}

			$rootScope.search.pagination.pages = [];
			for (var i = iniPag; i <= fimPag; i++)
				$rootScope.search.pagination.pages.push(i);

		}

		$scope.buscar = function(page) {

			page = page || $state.params.page;
			page = Number(page);

			if (page) {
				$rootScope.search.pagination.page = page;
				$rootScope.search.pagination.offset = (page - 1) * $rootScope.search.pagination.length;
			}

			if ($rootScope.search.order == 1)
				$rootScope.search.pagination.order = {
					nome: "asc"
				};
			else if ($rootScope.search.order == 2)
				$rootScope.search.pagination.order = {
					nome: "desc"
				};
			else if ($rootScope.search.order == 3)
				$rootScope.search.pagination.order = {
					valorreal: "desc"
				};
			else if ($rootScope.search.order == 4)
				$rootScope.search.pagination.order = {
					valorreal: "asc"
				};

			$rootScope.search.result = [];
			$rootScope.search.load = false;
			blockMain.start();
			$http.get($scope.backURL + 'api/marcas/' + mar  + '/produtos', {
				params: {
					offset: $rootScope.search.pagination.offset,
					limit: $rootScope.search.pagination.length,
					order: JSON.stringify($rootScope.search.pagination.order)
				}
			}).then(function(data) {
				$rootScope.search.result = data.data.data.result;
				$rootScope.search.pagination.total = data.data.data.total;
				$.each($rootScope.search.result, function(k, v){
					$rootScope.tratarProduto(v);
				})
				$timeout(function() {
					$("img.lazy").lazyload({
						effect: "fadeIn"
					});
					blockMain.stop();
					makePaginator();
					$rootScope.search.load = true;
				}, 100);
				$('html, body').animate({
					scrollTop: ($("#main").offset().top - 70)
				}, 500);
			}, function(error) {});

		};

		if ($rootScope.search.brand === undefined || $rootScope.search.brand.id != mar || $rootScope.search.category !== undefined){
			var idI = $interval(function() {
				if ($rootScope.produtosPorPagina !== undefined) {
					angular.extend($rootScope.search, {
						pagination: {
							offset: 0,
							length: $rootScope.produtosPorPagina,
							total: 0,
							viewPages: 10,
							totalPages: 1,
							order: {
								nome: "asc"
							},
							pages: [],
							page: 1,
							url: "/m/" + $state.params.idmarca + "/"
						},
						category: {},
						brand: {},
						term: undefined,
						result: [],
						breadcrumb: [],
						order: "1"
					});

					$rootScope.search.category = undefined;

					$http.get($scope.backURL + 'api/marcas/' + mar).then(function(data) {
						$rootScope.search.brand = data.data.data;
					}, function(error) {});

					$interval.cancel(idI);
					$scope.buscar();
				}
			}, 1000);
		} else {
			$timeout(function() {
				$("img.lazy").lazyload({
					effect: "fadeIn"
				});
			}, 100);
		}

		$('html, body').animate({
			scrollTop: ($("#main").offset().top - 70)
		}, 500);

	}])

	.controller('BuscaController', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$interval', 'blockUI', function($scope, $rootScope, $http, $state, $timeout, $interval, blockUI) {

		var busca = $state.params.busca,
			blockMain = blockUI;

		function makePaginator() {

			var pages = Number($rootScope.search.pagination.total / $rootScope.search.pagination.length),
				paginas = 0,
				iniPag = 0,
				fimPag = 0;
			pages = Number(pages.toFixed(0)) < pages ? Number(pages.toFixed(0)) + 1 : pages;
			$rootScope.search.pagination.totalPages = pages;
			if (pages > $rootScope.search.pagination.viewPages) {
				paginas = parseInt($rootScope.search.pagination.viewPages / 2);
				if ($rootScope.search.pagination.page + paginas > pages) {
					iniPag = pages - $rootScope.search.pagination.viewPages + 1;
					fimPag = pages;
				} else {
					iniPag = (($rootScope.search.pagination.page) - paginas < 1) ? 1 : (parseInt(paginas / 2) !== paginas / 2 ? ($rootScope.search.pagination.page) - (paginas) : ($rootScope.search.pagination.page) - (paginas - 1));
					fimPag = (iniPag + $rootScope.search.pagination.viewPages - 1 > pages) ? pages : iniPag + $rootScope.search.pagination.viewPages - 1;
					iniPag += (($rootScope.search.pagination.page) > fimPag - (paginas) && parseInt(paginas / 2) !== paginas / 2) ? (fimPag - paginas) - ($rootScope.search.pagination.page) : 0;
					iniPag = parseInt(iniPag);
				}
			} else {
				iniPag = 1;
				fimPag = pages;
			}

			$rootScope.search.pagination.pages = [];
			for (var i = iniPag; i <= fimPag; i++)
				$rootScope.search.pagination.pages.push(i);

		}

		$scope.buscar = function(page) {

			page = page || $state.params.page;
			page = Number(page);

			if (page) {
				$rootScope.search.pagination.page = page;
				$rootScope.search.pagination.offset = (page - 1) * $rootScope.search.pagination.length;
			}

			if ($rootScope.search.order == 1)
				$rootScope.search.pagination.order = {
					nome: "asc"
				};
			else if ($rootScope.search.order == 2)
				$rootScope.search.pagination.order = {
					nome: "desc"
				};
			else if ($rootScope.search.order == 3)
				$rootScope.search.pagination.order = {
					valorreal: "desc"
				};
			else if ($rootScope.search.order == 4)
				$rootScope.search.pagination.order = {
					valorreal: "asc"
				};

			$rootScope.search.result = [];
			$rootScope.search.load = false;
			blockMain.start();
			$http.get($scope.backURL + 'api/produtos', {
				params: {
					offset: $rootScope.search.pagination.offset,
					limit: $rootScope.search.pagination.length,
					order: JSON.stringify($rootScope.search.pagination.order),
					nome: '|' + busca,
					descricao: '|' + busca,
					codigo: '|(equals)' + busca,
          marca: $state.params.marca
				}
			}).then(function(data) {
				$rootScope.search.result = data.data.data.result;
				$rootScope.search.pagination.total = data.data.data.total;
				$.each($rootScope.search.result, function(k, v){
					$rootScope.tratarProduto(v);
				})
				$timeout(function() {
					$("img.lazy").lazyload({
						effect: "fadeIn"
					});
					blockMain.stop();
					makePaginator();
					$rootScope.search.load = true;
				}, 100);
				$('html, body').animate({
					scrollTop: ($("#main").offset().top - 70)
				}, 500);
			}, function(error) {});

		};

		if ($rootScope.search.term === undefined || $rootScope.search.term != busca) {
			var idI = $interval(function() {
				if ($rootScope.produtosPorPagina !== undefined) {
					angular.extend($rootScope.search, {
						pagination: {
							offset: 0,
							length: $rootScope.produtosPorPagina,
							total: 0,
							viewPages: 10,
							totalPages: 1,
							order: {
								nome: "asc"
							},
							pages: [],
							page: 1,
							url: "/search/" + busca + "/",
              query: '?'+($state.params.marca > 0 ? 'marca='+$state.params.marca : '')
						},
						term: busca,
						result: [],
						category: undefined,
						breadcrumb: undefined,
						order: "1"
					});
					$interval.cancel(idI);
					$scope.buscar();
				}
			}, 1000);
		} else {
			$timeout(function() {
				$("img.lazy").lazyload({
					effect: "fadeIn"
				});
			}, 100);
		}

		//if($(".category").offset().top < $(document).scrollTop()){
		$('html, body').animate({
			scrollTop: ($("#main").offset().top - 70)
		}, 500);
		//}

	}])
	
	.controller('ProdutosController', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$interval', 'blockUI', function($scope, $rootScope, $http, $state, $timeout, $interval, blockUI) {

		var blockMain = blockUI;

		function makePaginator() {

			var pages = Number($rootScope.search.pagination.total / $rootScope.search.pagination.length),
				paginas = 0,
				iniPag = 0,
				fimPag = 0;
			pages = Number(pages.toFixed(0)) < pages ? Number(pages.toFixed(0)) + 1 : pages;
			$rootScope.search.pagination.totalPages = pages;
			if (pages > $rootScope.search.pagination.viewPages) {
				paginas = parseInt($rootScope.search.pagination.viewPages / 2);
				if ($rootScope.search.pagination.page + paginas > pages) {
					iniPag = pages - $rootScope.search.pagination.viewPages + 1;
					fimPag = pages;
				} else {
					iniPag = (($rootScope.search.pagination.page) - paginas < 1) ? 1 : (parseInt(paginas / 2) !== paginas / 2 ? ($rootScope.search.pagination.page) - (paginas) : ($rootScope.search.pagination.page) - (paginas - 1));
					fimPag = (iniPag + $rootScope.search.pagination.viewPages - 1 > pages) ? pages : iniPag + $rootScope.search.pagination.viewPages - 1;
					iniPag += (($rootScope.search.pagination.page) > fimPag - (paginas) && parseInt(paginas / 2) !== paginas / 2) ? (fimPag - paginas) - ($rootScope.search.pagination.page) : 0;
					iniPag = parseInt(iniPag);
				}
			} else {
				iniPag = 1;
				fimPag = pages;
			}

			$rootScope.search.pagination.pages = [];
			for (var i = iniPag; i <= fimPag; i++)
				$rootScope.search.pagination.pages.push(i);

		}

		$scope.buscar = function(page) {

			page = page || $state.params.page;
			page = Number(page);

			if (page) {
				$rootScope.search.pagination.page = page;
				$rootScope.search.pagination.offset = (page - 1) * $rootScope.search.pagination.length;
			}

			if ($rootScope.search.order == 1)
				$rootScope.search.pagination.order = {
					nome: "asc"
				};
			else if ($rootScope.search.order == 2)
				$rootScope.search.pagination.order = {
					nome: "desc"
				};
			else if ($rootScope.search.order == 3)
				$rootScope.search.pagination.order = {
					valorreal: "desc"
				};
			else if ($rootScope.search.order == 4)
				$rootScope.search.pagination.order = {
					valorreal: "asc"
				};

			$rootScope.search.result = [];
			$rootScope.search.load = false;
			blockMain.start();
			$http.get($scope.backURL + 'api/produtos', {
				params: {
					offset: $rootScope.search.pagination.offset,
					limit: $rootScope.search.pagination.length,
					order: JSON.stringify($rootScope.search.pagination.order)
				}
			}).then(function(data) {
				$rootScope.search.result = data.data.data.result;
				$rootScope.search.pagination.total = data.data.data.total;
				$.each($rootScope.search.result, function(k, v){
					$rootScope.tratarProduto(v);
				})
				$timeout(function() {
					$("img.lazy").lazyload({
						effect: "fadeIn"
					});
					blockMain.stop();
					makePaginator();
					$rootScope.search.load = true;
				}, 100);
				$('html, body').animate({
					scrollTop: ($("#main").offset().top - 70)
				}, 500);
			}, function(error) {});

		};

		var idI = $interval(function() {
			if ($rootScope.produtosPorPagina !== undefined) {
				angular.extend($rootScope.search, {
					pagination: {
						offset: 0,
						length: $rootScope.produtosPorPagina,
						total: 0,
						viewPages: 10,
						totalPages: 1,
						order: {
							nome: "asc"
						},
						pages: [],
						page: 1,
						url: "/produtos",
            query: ''
					},
					term: busca,
					result: [],
					category: undefined,
					breadcrumb: undefined,
					order: "1"
				});
				$interval.cancel(idI);
				$scope.buscar();
			}
		}, 1000);

		//if($(".category").offset().top < $(document).scrollTop()){
		$('html, body').animate({
			scrollTop: ($("#main").offset().top - 70)
		}, 500);
		//}

	}])

	.controller('ProdutoController', ['$scope', '$rootScope', '$state', '$http', '$timeout', 'blockUI', function($scope, $rootScope, $state, $http, $timeout, blockUI) {

		var cat = $state.params.idcategoria.split("-")[0],
			prod = $state.params.idproduto.split("-")[0],
			blockMain = blockUI;

		blockMain.start();
		$scope.breadcrumb 				= [];
		$scope.gruposRelacionados	= [];

		function addBreadcrumb(categoria) {
			if (categoria.categoriapai !== null) {
				addBreadcrumb(categoria.categoriapai);
			}
			$scope.breadcrumb.push(categoria);
		}

		function makeProduto() {
			$rootScope.tratarProduto($rootScope.produto);
			if ($rootScope.produto.imagens) {
				$scope.imagens = {
					destaque: ($rootScope.produto.imagens.destaque ? $rootScope.produto.imagens.destaque : $rootScope.produto.imagens[0]),
					lista: function(){
						var lista = angular.copy($rootScope.produto.imagens),
								array = [],
								group = [];
						delete lista.destaque;
						$.each(lista, function(k, v){
							array.push(v);
						});
						while(array.length > 0){
							group.push(array.splice(0, 3));
						}
						return group;
					}()

				};
				$timeout(function() {
					$('.cycle-slideshow').cycle();
					blockMain.stop();
				}, 500);
			}
			//$rootScope.produto.descricao = nl2br($rootScope.produto.descricao);
			if($rootScope.produto.categorias.length > 0)
				$http.get($scope.backURL + 'api/categorias/' + $rootScope.produto.categorias[0].id + '/produtos', {
					params: {
						limit: 15
					}
				}).then(function(data) {
					$.each(data.data.data.result, function(k, v){
						$rootScope.tratarProduto(v);
					});
					while(data.data.data.result.length > 0){
						$scope.gruposRelacionados.push(data.data.data.result.splice(0, 5));
					}
				}, function(error) {});
		}

		function nl2br(str, is_xhtml) {
			var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
			return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
		}

		if ($rootScope.produto === undefined || $rootScope.produto === null) {
			$http.get($scope.backURL + 'api/categorias/' + cat + '/produtos/' + prod).then(function(data) {
				addBreadcrumb(data.data.data.categorias[0]);
				$rootScope.produto = data.data.data;
				$rootScope.categoria = data.data.data.categorias[0];
				makeProduto();
			}, function(error) {});
		} else {
			$http.get($scope.backURL + 'api/categorias/' + cat + '/produtos/' + prod).then(function(data) {
				addBreadcrumb(data.data.data.categorias[0]);
				$rootScope.produto = data.data.data;
				$rootScope.categoria = data.data.data.categorias[0];
				makeProduto();
			}, function(error) {});
			makeProduto();
		}

		if ($(".product-detail").offset().top < $(document).scrollTop()) {
			$('html, body').animate({
				scrollTop: ($(".product-detail").offset().top - 70)
			}, 500);
		}

	}])

	.controller('VendedoresController', ['$scope', '$rootScope', '$http', '$timeout', 'blockUI', function($scope, $rootScope, $http, $timeout, blockUI) {

		var blockMain = blockUI;

		blockMain.start();
		$scope.gruposVendedores = [];

		$('html, body').animate({
			scrollTop: ($("#main").offset().top - 70)
		}, 500);

		$http.get($rootScope.backURL + "api/vendedores").then(function(data) {
			$rootScope.sellers = angular.copy(data.data.data.result);
			$.each(data.data.data.result, function(k, v){
				if(v.imagem.imagem)
					v.imagem.imagem = $rootScope.backURL + '/lib.data/geral/textos/' + $filter('resizeImage')(v.imagem.imagem, 200, 164);
			});
			while(data.data.data.result.length > 0){
				$scope.gruposVendedores.push(data.data.data.result.splice(0, 3));
			}
			$timeout(function() {
			$('.cycle-slideshow').cycle();
				$("img.lazy").lazyload({
					effect: "fadeIn"
				});
				blockMain.stop();
			}, 100);
		}, function(error) {});

	}])

  .controller('MarcasController', ['$scope', '$rootScope', '$http', 'blockUI', '$timeout', function ($scope, $rootScope, $http, blockUI, $timeout){

    var blockMain = blockUI;

    blockMain.start();

    $('html, body').animate({
      scrollTop: ($("#main").offset().top - 70)
    }, 500);

    $http.get($scope.backURL + 'api/marcas', {
      params: {
        disponivel: 1
      }
    }).then(function (data) {
      $rootScope.brands = data.data.data.result;
      $timeout(function () {
        $("img.lazy").lazyload({
          effect: "fadeIn"
        });
        blockMain.stop();
        $('html, body').animate({
          scrollTop: ($("#main").offset().top - 70)
        }, 500);
      }, 100);
    }, function (error) {
    });

  }])

  .controller('EmpresaController', ['$scope', '$rootScope', '$http', 'blockUI', function($scope, $rootScope, $http, blockUI){
    var blockMain = blockUI.instances.get('blockui-main');
    blockMain.start();
    $http.get($rootScope.backURL+'api/textos/1').then(function(data){
      $scope.texto = data.data.data;
      blockMain.stop();
    }, function(error){});
    $('html, body').animate({
      scrollTop: ($(".simple-page").offset().top - 70)
    }, 500);
  }])

  .controller('AssistenciaController', ['$scope', '$rootScope', '$http', 'blockUI', function($scope, $rootScope, $http, blockUI){
    var blockMain = blockUI.instances.get('blockui-main');
    blockMain.start();
    $http.get($rootScope.backURL+'api/textos/2').then(function(data){
      $scope.texto = data.data.data;
      blockMain.stop();
    }, function(error){});
    $('html, body').animate({
      scrollTop: ($(".simple-page").offset().top - 70)
    }, 500);
  }])

  .controller('ContatoController', ['$scope', '$rootScope', '$http', 'blockUI', function($scope, $rootScope, $http, blockUI){
    var blockMain = blockUI.instances.get('blockui-main'),
        blockCon = blockUI.instances.get('form-contact');
    $scope.message = {
      error: null,
      success: null
    }
    blockMain.start();
    $http.get($rootScope.backURL+'api/textos/2').then(function(data){
      $scope.texto = data.data.data;
      blockMain.stop();
    }, function(error){});
    $('html, body').animate({
      scrollTop: ($(".simple-page").offset().top - 70)
    }, 500);
    $scope.enviar = function(){
      blockCon.start('Enviando mensagem...');
      $http.post($rootScope.backURL+'contato', {
        nome: $scope.nome,
        email: $scope.email,
        mensagem: $scope.mensagem
      }).then(function(data){
        if(data.data.success){
          $scope.message.success = data.data.message;
          $scope.message.error = null;
        }else{
          $scope.message.success = null;
          $scope.message.error = data.data.message;
        }
        blockCon.stop();
      }, function(error){
        $scope.message.success = null;
        $scope.message.error = "Mensagem não envianda, tente mais tarde.";
        blockCon.stop();
      });
      return false;
    };
  }])

  .controller('NovosController', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$interval', 'blockUI', function($scope, $rootScope, $http, $state, $timeout, $interval, blockUI) {

    var blockMain = blockUI.instances.get('blockui-main');

    $scope.buscar = function(page) {

      $rootScope.search.pagination.order = {
        id: "DESC"
      }

      $rootScope.search.result = [];
      $rootScope.search.load = false;
      blockMain.start();
      $http.get($scope.backURL + 'api/produtos', {
        params: {
          offset: 0,
          limit: $rootScope.search.pagination.length,
          order: JSON.stringify($rootScope.search.pagination.order),
          disponivel: 1
        }
      }).then(function(data) {
        $rootScope.search.result = data.data.data.result;
        $timeout(function() {
          $("img.lazy").lazyload({
            effect: "fadeIn"
          });
          blockMain.stop();
          $rootScope.search.load = true;
        }, 100);
        $('html, body').animate({
          scrollTop: ($(".product-list").offset().top - 70)
        }, 500);
      }, function(error) {});

    };

    var idI = $interval(function() {
      if ($rootScope.produtosPorPagina !== undefined) {
        angular.extend($rootScope.search, {
          pagination: {
            offset: 0,
            length: $rootScope.produtosPorPagina,
            total: 0,
            viewPages: 10,
            totalPages: 1,
            order: {
              nome: "asc"
            },
            pages: [],
            page: 1,
            url: '/novos'
          },
          category: {},
          brand: {},
          term: undefined,
          result: [],
          breadcrumb: [],
          order: "1"
        });
        $interval.cancel(idI);
        $scope.buscar();
      }
    }, 1000);

    $('html, body').animate({
      scrollTop: ($(".product-list").offset().top - 70)
    }, 500);

  }])

	.controller('ListaPrecosController', [function() {


		$('html, body').animate({
			scrollTop: ($(".price-list").offset().top - 70)
		}, 500);

	}])

	.controller('LocalizacaoController', [function() {

		$('html, body').animate({
			scrollTop: ($(".location").offset().top - 70)
		}, 500);

		$.getScript("http://maps.google.com/maps/api/js?sensor=true")
			.done(function(script, textStatus) {

				var map;
				//var pioneer = new google.maps.LatLng(-25.5055776, -54.6073596);
				var pioneer = new google.maps.LatLng(-25.50742, -54.608763);

				var MY_MAPTYPE_ID = 'custom_style';

				function initialize() {
					var mapcanvasdark = $('.map-canvas-dark').length;
					if (mapcanvasdark == 1) {
						var featureOpts = [{
							"stylers": [{
								"saturation": -100
							}]
						}];

					} else {
						var featureOpts = [];
					}

					var mapOptions = {
						zoom: 16,
						draggable: false,
						center: pioneer,
						disableDefaultUI: true,
						scrollwheel: false,
						mapTypeControlOptions: {
							mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
						},
						mapTypeId: MY_MAPTYPE_ID
					};

					map = new google.maps.Map(document.getElementById('map-canvas'),
						mapOptions);

					var styledMapOptions = {
						name: 'Custom Style'
					};

					var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
					map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
				}
				initialize();
				//google.maps.event.addDomListener(window, 'load', initialize);
			})
			.fail(function(jqxhr, settings, exception) {

			});

	}])

	.controller('CasasCambioController', [function() {


		$('html, body').animate({
			scrollTop: ($(".currency-exchange").offset().top - 70)
		}, 500);

	}]);

}).call(this);
