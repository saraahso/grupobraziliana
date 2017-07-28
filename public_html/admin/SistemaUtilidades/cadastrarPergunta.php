<?php

importar("Utilidades.FAQ.Lista.ListaPerguntaCategorias");

$tituloPagina = 'Utilidades > FAQ > Perguntas > Cadastrar';

$iTP = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/pergunta.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $p 			= new Pergunta;
		
		$p->titulo 		= $_POST['titulo'];		
		$p->ordem		= $_POST['ordem'];
		$p->texto		= $_POST['texto'];
		
		if(!empty($_FILES['imagem']['name']))
			$p->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		
		$lPC			= new ListaPerguntaCategorias;
		$pC				= $lPC->condicoes('', $_POST['categoria'], ListaPerguntaCategorias::ID)->listar();
		$pC->setPergunta($p);
		$p->getURL()->setURL($_POST['url'] ? $_POST['url'] : $p->getId()."-".strtolower(Strings::__RemoveAcentos(str_replace("\"", "", str_replace("'", "", str_replace(" ", "-", ($_POST['titulo'])))))));
		$lP = new ListaPerguntas;
		$lP->alterar($p);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($p->getId());
			$t->setCampoConteudo(ListaPerguntas::TITULO);
			$t->setTabelaConteudo($pC->getPerguntas()->getTabela());
			$t->conteudo = $p->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaPerguntas::TEXTO);
			$t->conteudo = $p->texto;
			$t->traducao = $_POST['itexto'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Pergunta salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTP->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarPerguntas");

$iTP->trocar("id.Categoria", $_POST['categoria']);
$iTP->trocar("titulo", $_POST['titulo']);
$iTP->trocar("url", $_POST['url']);
$iTP->trocar("ordem", $_POST['ordem']);
$iTP->trocar("texto", $_POST['texto']);


$sub 	= "repetir->titulo.Perguntas.Idiomas";
$sub2 	= "repetir->texto.Perguntas.Idiomas";
$iTP->createRepeticao($sub);
$iTP->createRepeticao($sub2);
while($i = $lI->listar()){
	
	$iTP->repetir($sub);
	$iTP->repetir($sub2);
	
	$iTP->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTP->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTP->enterRepeticao($sub)->trocar("titulo.Pergunta.Idioma", $_POST['ititulo'][$i->getId()]);
	
	$iTP->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTP->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTP->enterRepeticao($sub2)->trocar("texto.Pergunta.Idioma", $_POST['itexto'][$i->getId()]);
	
}

$iTP->createJavaScript();
$javaScript .= $iTP->javaScript->concluir();

$includePagina = $iTP->concluir();

?>