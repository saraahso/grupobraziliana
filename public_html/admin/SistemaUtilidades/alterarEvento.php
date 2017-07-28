<?php

importar("Utilidades.Lista.ListaEventos");

$tituloPagina = 'Utilidades > Eventos - Agenda > Alterar';

$iAT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/evento.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";
	if(empty($_POST['url']))
	    $erro = "<b>URL</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lE = new ListaEventos;
		$lE->condicoes('', $_GET['evento'], ListaEventos::ID);
		$eve = $lE->listar();
		
		$eve->getTexto()->titulo = $_POST['titulo'];
		$eve->local = $_POST['local'];
		$eve->getURL()->setURL($_POST['url']);
		$eve->getTexto()->texto	= html_entity_decode($_POST['texto']);
		$eve->setData(new DataHora($_POST['data']));
		
		if(!empty($_FILES['imagem']['name']))
			$eve->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lE->alterar($eve);
		
		$lT = new listaTextos;
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaTextos::TITULO, $lT->getTabela(), $eve->getTexto()->getId());
			
			if($t->getId()){
				
				$t->conteudo = $eve->getTexto()->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $eve->getTexto()->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($eve->getTexto()->getId());
				$t->setCampoConteudo(ListaTextos::TITULO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaTextos::TEXTO, $lT->getTabela(), $eve->getTexto()->getId());
			
			if($t->getId()){
				
				$t->conteudo = $eve->getTexto()->texto;
				$t->traducao = $_POST['itexto'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $eve->getTexto()->texto;
				$t->traducao = $_POST['itexto'][$i->getId()];
				$t->setIdConteudo($eve->getTexto()->getId());
				$t->setCampoConteudo(ListaTextos::TEXTO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Evento salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lE = new ListaEventos;
$t = $lE->condicoes('', $_GET['evento'], ListaEventos::ID)->listar();

$lT = new ListaTextos;

$iAT->trocar("titulo",		$t->getTexto()->titulo);
$iAT->trocar("local",		$t->local);
$iAT->trocar("url", 		$t->getURL()->getURL());
$iAT->trocar("data", 		$t->getData()->mostrar("d/m/Y"));
$iAT->trocar("texto", 		$t->getTexto()->texto);

$sub 	= "repetir->titulo.Eventos.Idiomas";
$sub2 	= "repetir->texto.Eventos.Idiomas";
$iAT->createRepeticao($sub);
$iAT->createRepeticao($sub2);
while($i = $lI->listar()){
	
	$iAT->repetir($sub);
	$iAT->repetir($sub2);
	
	$iAT->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub)->trocar("titulo.Evento.Idioma", $i->getTraducaoById(ListaEventos::TITULO, $lE->getTabela(), $t->getId())->traducao);
	
	$iAT->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub2)->trocar("texto.Evento.Idioma", $i->getTraducaoById(ListaEventos::TEXTO, $lE->getTabela(), $t->getId())->traducao);
	
}

$iAT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarEventos");
												   
if($t->getTexto()->getImagem()->getImage()->nome != '')
	$iAT->trocar("imagem", $t->getTexto()->getImagem()->getImage()->showHTML(200, 200));

$iAT->createJavaScript();
$javaScript .= $iAT->javaScript->concluir();

$includePagina = $iAT->concluir();

?>