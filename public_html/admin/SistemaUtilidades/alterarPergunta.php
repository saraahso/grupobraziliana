<?php

importar("Utilidades.FAQ.Lista.ListaPerguntas");

$tituloPagina = 'Utilidades > FAQ > Perguntas > Alterar';

$iTAP = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/pergunta.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lP = new ListaPerguntas;
		$lP->condicoes('', $_GET['pergunta'], ListaPerguntas::ID);
		$p = $lP->listar();
		
		$p->titulo 	= $_POST['titulo'];
		$p->ordem	= $_POST['ordem'];
		$p->getURL()->setURL($_POST['url'] ? $_POST['url'] : $p->getId()."-".strtolower(Strings::__RemoveAcentos(str_replace("\"", "", str_replace("'", "", str_replace(" ", "-", ($_POST['titulo'])))))));
		$p->texto	= $_POST['texto'];
		
		if(!empty($_FILES['imagem']['name']))
			$p->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$p->setIdCategoria($_POST['categoria']);
		
		$lP->alterar($p);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaPerguntas::TITULO, $lP->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaPerguntas::TITULO);
				$t->setTabelaConteudo($lP->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaPerguntas::TEXTO, $lP->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->texto;
				$t->traducao = $_POST['itexto'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->texto;
				$t->traducao = $_POST['itexto'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaPerguntas::TEXTO);
				$t->setTabelaConteudo($lP->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Pergunta salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lP = new ListaPerguntas;
$p = $lP->condicoes('', $_GET['pergunta'], ListaPerguntas::ID)->listar();

$iTAP->trocar("id.Categoria",	$p->getIdCategoria());
$iTAP->trocar("titulo",			$p->titulo);
$iTAP->trocar("url", 			$p->getURL()->getURL());
$iTAP->trocar("ordem", 			$p->ordem);
$iTAP->trocar("texto", 			$p->texto);

$sub 	= "repetir->titulo.Perguntas.Idiomas";
$sub2 	= "repetir->texto.Perguntas.Idiomas";
$iTAP->createRepeticao($sub);
$iTAP->createRepeticao($sub2);
while($i = $lI->listar()){
	
	$iTAP->repetir($sub);
	$iTAP->repetir($sub2);
	
	$iTAP->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTAP->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTAP->enterRepeticao($sub)->trocar("titulo.Pergunta.Idioma", $i->getTraducaoById(ListaPerguntas::TITULO, $lP->getTabela(), $p->getId())->traducao);
	
	$iTAP->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTAP->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iTAP->enterRepeticao($sub2)->trocar("texto.Pergunta.Idioma", $i->getTraducaoById(ListaPerguntas::TEXTO, $lp->getTabela(), $p->getId())->traducao);
	
}

$iTAP->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarPerguntas");
												   
if($p->getImagem()->getImage()->nome != '')
	$iTAP->trocar("imagem", $p->getImagem()->getImage()->showHTML(200, 200));

$iTAP->createJavaScript();
$javaScript .= $iTAP->javaScript->concluir();

$includePagina = $iTAP->concluir();

?>