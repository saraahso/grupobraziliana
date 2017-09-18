<?php

class Sistema {
	
	public static $BDHost 							= 'localhost';
	public static $BDBanco							= 'c9';
	public static $BDUsuario						= 'jhonnybail'; //onoteatrouser
	public static $BDSenha 							= ''; //#8/wkVTFx=
	public static $BDPrefixo						= 'tta_';
	
	public static $caminhoDiretorio					= '/';
	public static $caminhoURL		 				= '/';
	
	public static $layoutTemplate					= 'v3';
	public static $layoutCaminhoDiretorio			= '';
	public static $layoutCaminhoURL					= '';
	
	public static $adminCaminhoDiretorio			= 'admin/';
	public static $adminCaminhoURL		 			= 'admin/';
	
	public static $adminLayoutTemplate				= 'v1';
	public static $adminLayoutCaminhoDiretorio		= '';
	public static $adminLayoutCaminhoURL			= '';
	
	public static $nomeEmpresa 						= 'Grupo Braziliana';
	public static $dominioEmpresa					= '';
	public static $emailEmpresa 					= '';
	public static $sistemaVersao					= 'Versão Beta 2.0';
	
	public static $emailsPorHora					= 9999;
	
	public static $caminhoData						= 'lib.data/';
	public static $caminhoDataIdiomas				= 'configuracoes/';
	public static $caminhoDataProdutoCategorias		= 'produtos/categorias/';
	public static $caminhoDataProdutoMarcas			= 'produtos/marcas/';
	public static $caminhoDataProdutoOpcoes			= 'produtos/opcoes/';
	public static $caminhoDataProdutos				= 'produtos/';
	public static $caminhoDataOfertasColetivas		= 'ofertascoletivas/';
	public static $caminhoDataTextos				= 'geral/textos/';
	public static $caminhoDataUsuarios				= 'geral/usuarios/';
	public static $caminhoDataGalerias				= 'utilidades/galerias/';
	public static $caminhoDataFAQ					= 'utilidades/faq/';
	public static $caminhoDataBanners				= 'utilidades/publicidades/banners/';
	public static $caminhoDataUploadsDownloads		= 'utilidades/uploadsdownloads/';
	public static $caminhoDataDiscografia			= 'utilidades/discografia/';
	public static $caminhoDataSlides				= 'utilidades/publicidades/slides/';
	public static $caminhoDataTickets				= 'utilidades/tickets/';
	public static $caminhoDataPessoasPerfil			= 'pessoas/perfil/';
	
}

Sistema::$dominioEmpresa				= str_replace('www.', '', $_SERVER['HTTP_HOST']);
Sistema::$caminhoDiretorio				= dirname(__FILE__)."/..".Sistema::$caminhoDiretorio;
Sistema::$caminhoURL					= $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].Sistema::$caminhoURL;
Sistema::$layoutCaminhoDiretorio 		= Sistema::$caminhoDiretorio.'lib.templates/'.Sistema::$layoutTemplate.'/';
Sistema::$layoutCaminhoURL				= Sistema::$caminhoURL.'lib.templates/'.Sistema::$layoutTemplate.'/';

Sistema::$adminCaminhoDiretorio			= Sistema::$caminhoDiretorio.Sistema::$adminCaminhoDiretorio;
Sistema::$adminCaminhoURL				= Sistema::$caminhoURL.Sistema::$adminCaminhoURL;
Sistema::$adminLayoutCaminhoDiretorio 	= Sistema::$adminCaminhoDiretorio.'lib.templates/'.Sistema::$adminLayoutTemplate.'/';
Sistema::$adminLayoutCaminhoURL			= Sistema::$adminCaminhoURL.'lib.templates/'.Sistema::$adminLayoutTemplate.'/';

Sistema::$caminhoData					= Sistema::$caminhoData;
Sistema::$caminhoDataIdiomas			= Sistema::$caminhoData.Sistema::$caminhoDataIdiomas;
Sistema::$caminhoDataProdutoCategorias	= Sistema::$caminhoData.Sistema::$caminhoDataProdutoCategorias;
Sistema::$caminhoDataProdutoMarcas		= Sistema::$caminhoData.Sistema::$caminhoDataProdutoMarcas;
Sistema::$caminhoDataProdutoOpcoes		= Sistema::$caminhoData.Sistema::$caminhoDataProdutoOpcoes;
Sistema::$caminhoDataProdutos			= Sistema::$caminhoData.Sistema::$caminhoDataProdutos;
Sistema::$caminhoDataOfertasColetivas	= Sistema::$caminhoData.Sistema::$caminhoDataOfertasColetivas;
Sistema::$caminhoDataTextos				= Sistema::$caminhoData.Sistema::$caminhoDataTextos;
Sistema::$caminhoDataUsuarios			= Sistema::$caminhoData.Sistema::$caminhoDataUsuarios;
Sistema::$caminhoDataGalerias			= Sistema::$caminhoData.Sistema::$caminhoDataGalerias;
Sistema::$caminhoDataFAQ				= Sistema::$caminhoData.Sistema::$caminhoDataFAQ;
Sistema::$caminhoDataBanners			= Sistema::$caminhoData.Sistema::$caminhoDataBanners;
Sistema::$caminhoDataUploadsDownloads	= Sistema::$caminhoData.Sistema::$caminhoDataUploadsDownloads;
Sistema::$caminhoDataDiscografia		= Sistema::$caminhoData.Sistema::$caminhoDataDiscografia;
Sistema::$caminhoDataSlides				= Sistema::$caminhoData.Sistema::$caminhoDataSlides;
Sistema::$caminhoDataTickets			= Sistema::$caminhoData.Sistema::$caminhoDataTickets;
Sistema::$caminhoDataPessoasPerfil		= Sistema::$caminhoData.Sistema::$caminhoDataPessoasPerfil;

?>