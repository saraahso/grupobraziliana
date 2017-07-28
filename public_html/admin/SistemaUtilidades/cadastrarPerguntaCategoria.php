<?php

importar("Utilidades.FAQ.Lista.ListaPerguntaCategorias");

$tituloPagina = 'Utilidades > FAQ > Categorias > Cadastrar';

$iTPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/perguntaCategoria.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $pC 			= new PerguntaCategoria;
		
		$pC->titulo 	= $_POST['titulo'];		
		$pC->ordem		= $_POST['ordem'];
		
		$lT				= new ListaPerguntaCategorias;
		$lT->inserir($pC);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($pC->getId());
			$t->setCampoConteudo(ListaPerguntaCategorias::TITULO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $pC->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTPC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarPerguntaCategorias");

$iTPC->trocar("titulo", $_POST['titulo']);
$iTPC->trocar("ordem", $_POST['ordem']);


$sub 	= "repetir->titulo.PerguntaCategorias.Idiomas";

$iTPC->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTPC->repetir($sub);
;
	
	$iTPC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTPC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTPC->enterRepeticao($sub)->trocar("titulo.PerguntaCategoria.Idioma", $_POST['ititulo'][$i->getId()]);
	
	
}

$iTPC->createJavaScript();
$javaScript .= $iTPC->javaScript->concluir();

$includePagina = $iTPC->concluir();

?>