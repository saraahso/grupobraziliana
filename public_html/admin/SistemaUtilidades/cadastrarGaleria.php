<?php

importar("Utilidades.Galerias.Lista.ListaGalerias");

$tituloPagina = 'Utilidades > Galerias > Cadastrar';

$iTG = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/galeria.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
		
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
	    $g 			= new Galeria;
		
		$g->titulo 		= $_POST['titulo'];
		$g->local		= $_POST['local'];
		$g->tipo		= $_POST['tipo'];
		$g->setData(new DataHora($_POST['data']));
		$g->setVideo($_POST['video']);
		$g->descricao 	= $_POST['descricao'];
		$g->ordem	 	= $_POST['ordem'];
		
		$lG				= new ListaGalerias;
		$lG->inserir($g);
		$g->getURL()->setURL($_POST['url'] ? $_POST['url'] : $g->getId()."-".URL::cleanURL(($_POST['titulo'])));
		$lG->alterar($g);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($g->getId());
			$t->setCampoConteudo(ListaGalerias::TITULO);
			$t->setTabelaConteudo($lG->getTabela());
			$t->conteudo = $g->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaGalerias::DESCRICAO);
			$t->conteudo = $g->descricao;
			$t->traducao = $_POST['idescricao'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$_POST = '';
		
		header("Location: ?p=SistemaUtilidades&a=alterarGaleria&galeria=".$g->getId());
		
	    $javaScript .= Aviso::criar("Galeria salva com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iTG->condicao('condicao->alterar.Galeria', true);

$iTG->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarGalerias");

$iTG->trocar("titulo", 			$_POST['titulo']);
$iTG->trocar("url", 			$_POST['url']);
$iTG->trocar("data", 			$_POST['data']);
$iTG->trocar("local", 			$_POST['local']);
$iTG->trocar("tipo", 			$_POST['tipo']);
$iTG->trocar("descricao", 		$_POST['descricao']);
$iTG->trocar("ordem",	 		$_POST['ordem']);

$iTG->trocar("video", 			$_POST['video']);

$iTG->createRepeticao("repetir->Imagens.Galeria");

$sub 	= "repetir->titulo.Galerias.Idiomas";
$sub2 	= "repetir->descricao.Galerias.Idiomas";
$iTG->createRepeticao($sub);
$iTG->createRepeticao($sub2);
while($i = $lI->listar()){
	
	$iTG->repetir($sub);
	$iTG->repetir($sub2);
	
	$iTG->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTG->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTG->enterRepeticao($sub)->trocar("titulo.Galeria.Idioma", $_POST['ititulo'][$i->getId()]);
	
	$iTG->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTG->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTG->enterRepeticao($sub2)->trocar("descricao.Galeria.Idioma", $_POST['idescricao'][$i->getId()]);
	
}

$iTG->createJavaScript();
$javaScript .= $iTG->javaScript->concluir();

$includePagina = $iTG->concluir();

?>