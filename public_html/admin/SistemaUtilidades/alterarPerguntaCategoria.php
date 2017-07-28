<?php

importar("Utilidades.FAQ.Lista.ListaPerguntaCategorias");

$pCituloPagina = 'Utilidades > FAQ > Categorias > Alterar';

$iTPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/perguntaCategoria.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lPC = new ListaPerguntaCategorias;
		$lPC->condicoes('', $_GET['categoria'], ListaPerguntaCategorias::ID);
		$pC = $lPC->listar();
		
		$pC->titulo 	= $_POST['titulo'];
		$pC->ordem	= $_POST['ordem'];
		
		$lPC->alterar($pC);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaPerguntaCategorias::TITULO, $lPC->getTabela(), $pC->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($pC->getId());
				$t->setCampoConteudo(ListaPerguntaCategorias::TITULO);
				$t->setTabelaConteudo($lPC->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lPC = new ListaPerguntaCategorias;
$pC = $lPC->condicoes('', $_GET['categoria'], ListaPerguntaCategorias::ID)->listar();

$iTPC->trocar("titulo",		$pC->titulo);
$iTPC->trocar("ordem", 		$pC->ordem);

$sub 	= "repetir->titulo.PerguntaCategorias.Idiomas";

$iTPC->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTPC->repetir($sub);
	
	$iTPC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTPC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTPC->enterRepeticao($sub)->trocar("titulo.PerguntaCategoria.Idioma", $i->getTraducaoById(ListaPerguntaCategorias::TITULO, $lSC->getTabela(), $pC->getId())->traducao);
	
}

$iTPC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarPerguntaCategorias");

$iTPC->createJavaScript();
$javaScript .= $iTPC->javaScript->concluir();

$includePagina = $iTPC->concluir();

?>