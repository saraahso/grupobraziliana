<?php

importar("Utilidades.Publicidades.Banners.Lista.ListaBanners");

$tituloPagina = 'Utilidades > Publicidades > Banners > Alterar';

$iTAB = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/banner.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lB = new ListaBanners;
		$lB->condicoes('', $_GET['banner'], ListaBanners::ID);
		$b = $lB->listar();
		
		$b->titulo 		= $_POST['titulo'];		
		$b->enderecoURL	= $_POST['enderecoURL'];
		$b->tipo		= $_POST['tipo'];
		
		$b->setDataInicio(new DataHora($_POST['dataInicio']));
		$b->setDataFim(new DataHora($_POST['dataFim']));		
		
		$b->ativo		= $_POST['ativo'] == ListaBanners::VALOR_ATIVO_TRUE ? true : false;
		
		if(!empty($_FILES['flash']['name'])){
			$arq = Arquivos::__OpenArquivoByTEMP($_FILES['flash']);
			$arq->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataBanners);
			$b->setFlash($_FILES['flash']['name']);
		}
		
		$b->setLargura($_POST['largura']);
		$b->setAltura($_POST['altura']);
		
		if(!empty($_FILES['imagem']['name']))
			$b->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lB->alterar($b);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaBanners::TITULO, $lB->getTabela(), $b->getId());
			
			if($t->getId()){
				$t->conteudo = $b->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $b->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($b->getId());
				$t->setCampoConteudo(ListaBanners::TITULO);
				$t->setTabelaConteudo($lB->getTabela());
				
				$i->addTraducao($t);
				
			}
			
		}
		
		$con = BDConexao::__Abrir();
		$con->deletar(Sistema::$BDPrefixo."relacionamento_banners_categorias", "WHERE banner = '".$b->getId()."'");
		
		$lBC = new ListaBannerCategorias;
		
		if(count($_POST['categoriasSelecionadas']) > 0){
		
			foreach($_POST['categoriasSelecionadas'] as $valor){
				
				$lBC->condicoes('', $valor, ListaBannerCategorias::ID);
				
				if($lBC->getTotal() > 0){
					
					$bC = $lBC->listar();
					
					$b->addCategoria($bC);
					
				}
				
			}
		
		}
		
	    $javaScript .= Aviso::criar("Banner salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lB = new ListaBanners;
$b = $lB->condicoes('', $_GET['banner'], ListaBanners::ID)->listar();

$iTAB->condicao('condicao->alterar.Banner', false);

$iTAB->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarBanners");

$iTAB->trocar("titulo", 			$b->titulo);
$iTAB->trocar("enderecoURL",		$b->enderecoURL);
$iTAB->trocar("clicks", 			$b->clicks);
$iTAB->trocar("tipo", 				$b->tipo);

$iTAB->trocar("dataInicio", 		$b->getDataInicio()->mostrar("d/m/Y H:i:s"));
$iTAB->trocar("dataFim",			$b->getDataFim()->mostrar("d/m/Y H:i:s"));

$iTAB->trocar("ativo", 				$b->ativo 	? 1 : 0);

if($b->getImagem()->nome != '')
	$iTAB->trocar("imagem", 		$b->getImagem()->showHTML(200, 200));
	
if($b->getFlash() != ''){
	$iTAB->trocar("url.Flash", 		Sistema::$caminhoURL."lib.data/utilidades/publicidades/banners/".$b->getFlash());
	$iTAB->trocar("largura", 		$b->getLargura()->formatar() > 0 ? $b->getLargura()->formatar() : '');
	$iTAB->trocar("altura", 		$b->getAltura()->formatar() > 0 ? $b->getAltura()->formatar() : '');
}

$iTAB->createRepeticao("repetir->BannerCategorias.Banner");
while($bC = $b->getCategorias()->listar()){
	
	$iTAB->repetir();
	$iTAB->enterRepeticao()->trocar('id.BannerCategoria.Banner', $bC->getId());
	$iTAB->enterRepeticao()->trocar('titulo.BannerCategoria.Banner', $bC->titulo);
	
}

$lI 	= new ListaIdiomas;
$sub 	= "repetir->titulo.Banners.Idiomas";

$iTAB->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTAB->repetir($sub);
;
	
	$iTAB->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTAB->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTAB->enterRepeticao($sub)->trocar("titulo.Banner.Idioma", $i->getTraducaoById(ListaBanners::TITULO, $lB->getTabela(), $b->getId())->traducao);
	
}

$iTAB->createJavaScript();
$javaScript .= $iTAB->javaScript->concluir();

$includePagina = $iTAB->concluir();

?>