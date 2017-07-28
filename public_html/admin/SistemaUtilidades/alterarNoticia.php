<?php

importar("Utilidades.Noticias.Lista.ListaNoticias");

$tituloPagina = 'Utilidades > Noticias > Alterar';

$iAT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/noticia.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lN = new ListaNoticias;
		$lN->condicoes('', $_GET['noticia'], ListaNoticias::ID);
		$not = $lN->listar();
		
		$not->getTexto()->titulo = $_POST['titulo'];
		$not->getTexto()->subTitulo = $_POST['subTitulo'];
		$not->getURL()->setURL($_POST['url'] ? $_POST['url'] : $not->getId()."-".URL::cleanURL($_POST['titulo']));
		$not->getTexto()->texto	= $_POST['texto'];
		$not->setData(new DataHora($_POST['data']));
		
		if(!empty($_FILES['imagem']['name']))
			$not->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lN->alterar($not);
		
		$lT = new ListaTextos;
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaTextos::TITULO, $lT->getTabela(), $not->getTexto()->getId());
			
			if($t){
				
				$t->conteudo = $not->getTexto()->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $not->getTexto()->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($not->getTexto()->getId());
				$t->setCampoConteudo(ListaTextos::TITULO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaTextos::SUBTITULO, $lT->getTabela(), $not->getTexto()->getId());
			
			if($t){
				
				$t->conteudo = $not->getTexto()->subTitulo;
				$t->traducao = $_POST['isubTitulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $not->getTexto()->subTitulo;
				$t->traducao = $_POST['isubTitulo'][$i->getId()];
				$t->setIdConteudo($not->getTexto()->getId());
				$t->setCampoConteudo(ListaTextos::SUBTITULO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaTextos::TEXTO, $lT->getTabela(), $not->getTexto()->getId());
			
			if($t){
				
				$t->conteudo = $not->getTexto()->texto;
				$t->traducao = $_POST['itexto'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $not->getTexto()->texto;
				$t->traducao = $_POST['itexto'][$i->getId()];
				$t->setIdConteudo($not->getTexto()->getId());
				$t->setCampoConteudo(ListaTextos::TEXTO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
		$con = BDConexao::__Abrir();
		$con->deletar(Sistema::$BDPrefixo."relacionamento_noticias_categorias", "WHERE noticia = '".$not->getId()."'");
		
		$lGC = new ListaNoticiaCategorias;
		
		if(count($_POST['categoriasSelecionadas']) > 0){
		
			for($i = count($_POST['categoriasSelecionadas'])-1; $i >= 0; $i--){
				
				$valor = $_POST['categoriasSelecionadas'][$i];
				
				$lGC->condicoes('', $valor, ListaNoticiaCategorias::ID);
				
				if($lGC->getTotal() > 0){
					
					$gC = $lGC->listar();
					
					$not->addCategoria($gC);
					
				}
				
			}
		
		}
		
		try{
			Sistema::gerarRSS($not->getTexto()->titulo, $not->getTexto()->texto, Sistema::$caminhoURL."br/noticias/".$not->getCategorias()->listar()->getURL()->url."/".$not->getURL()->url, null);
		}catch(Exception $e){
			
		}
		
	    $javaScript .= Aviso::criar("Noticia salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iAT->condicao('condicao->alterar.Noticia', false);

$lN = new ListaNoticias;
$t = $lN->condicoes('', $_GET['noticia'], ListaNoticias::ID)->listar();

$lT = new ListaTextos;

$iAT->trocar("titulo",		$t->getTexto()->titulo);
$iAT->trocar("subTitulo",	$t->getTexto()->subTitulo);
$iAT->trocar("url", 		$t->getURL()->getURL());
$iAT->trocar("data", 		$t->getData()->mostrar("d/m/Y H:i"));
$iAT->trocar("texto", 		$t->getTexto()->texto);

$iAT->createRepeticao("repetir->NoticiaCategorias.Noticia");
while($sC = $t->getCategorias()->listar()){
	
	$iAT->repetir();
	$iAT->enterRepeticao()->trocar('id.NoticiaCategoria.Noticia', $sC->getId());
	$iAT->enterRepeticao()->trocar('titulo.NoticiaCategoria.Noticia', $sC->getTexto()->titulo);
	
}

$sub 	= "repetir->titulo.Noticias.Idiomas";
$sub2 	= "repetir->texto.Noticias.Idiomas";
$sub3 	= "repetir->subTitulo.Noticias.Idiomas";
$iAT->createRepeticao($sub);
$iAT->createRepeticao($sub2);
$iAT->createRepeticao($sub3);
while($i = $lI->listar()){
	
	$iAT->repetir($sub);
	$iAT->repetir($sub2);
	$iAT->repetir($sub3);
	
	$iAT->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub)->trocar("titulo.Noticia.Idioma", $i->getTraducaoByConteudo($t->getTexto()->titulo, $lT->getTabela())->traducao);
	
	$iAT->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub2)->trocar("texto.Noticia.Idioma", $i->getTraducaoByConteudo($t->getTexto()->texto, $lT->getTabela())->traducao);
	
	$iAT->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub3)->trocar("subTitulo.Noticia.Idioma", $i->getTraducaoByConteudo($t->getTexto()->subTitulo, $lT->getTabela())->traducao);
	
}

$iAT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarNoticias");
												   
if($t->getTexto()->getImagem()->getImage()->nome != '')
	$iAT->trocar("imagem", $t->getTexto()->getImagem()->getImage()->showHTML(200, 200));

$iAT->createJavaScript();
$javaScript .= $iAT->javaScript->concluir();

$includePagina = $iAT->concluir();

?>