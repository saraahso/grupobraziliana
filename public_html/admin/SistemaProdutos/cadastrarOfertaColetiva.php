<?php

importar("LojaVirtual.Produtos.Lista.ListaOfertasColetivas");

$tituloPagina = 'Produtos > Ofertas Colevitas > Ofertas > Cadastrar';

$iCOC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/ofertaColetiva.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
		
    if(empty($_POST['titulo']))
	    $erro = "<b>Título</b> não preenchido!<br><br>";
	if(empty($_POST['url']))
	    $erro = "<b>URL</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
	    $p 					= new OfertaColetiva;
		
		$p->titulo 			= $_POST['titulo'];		
		$p->subTitulo		= $_POST['subTitulo'];
		$p->getURL()->setURL($_POST['url']);
		
		$p->valorOriginal	= $_POST['valorOriginal'];
		$p->desconto		= $_POST['desconto'];
		$p->economia		= $_POST['economia'];
		$p->valor			= $_POST['valor'];
		$p->quantidade		= $_POST['quantidade'];
		$p->comprasMinima	= $_POST['comprasMinima'];
		$p->comprasMaxima	= $_POST['comprasMaxima'];
		
		$p->destaques		= $_POST['destaques'];
		$p->regulamento		= $_POST['regulamento'];
		
		$lEOF = new ListaEmpresasOfertaColetiva;
		$lEOF->condicoes('', $_POST['empresa'], ListaEmpresasOfertaColetiva::ID);
		if($lEOF->getTotal() > 0)
			$p->setEmpresa($lEOF->listar());
		
		$p->setDataInicio(new DataHora($_POST['dataInicio']));
		$p->setDataFim(new DataHora($_POST['dataFim']));
		$p->setValidadeInicio(new DataHora($_POST['validadeInicio']));
		$p->setValidadeFim(new DataHora($_POST['validadeFim']));
		
		$lOC				= new ListaOfertasColetivas;
		$lOC->inserir($p);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($p->getId());
			$t->setCampoConteudo(ListaOfertasColetivas::TITULO);
			$t->setTabelaConteudo($lOC->getTabela());
			$t->conteudo = $p->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaOfertasColetivas::SUBTITULO);
			$t->conteudo = $p->subTitulo;
			$t->traducao = $_POST['isubTitulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaOfertasColetivas::DESTAQUES);
			$t->conteudo = $p->destaques;
			$t->traducao = $_POST['idestaques'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaOfertasColetivas::REGULAMENTO);
			$t->conteudo = $p->regulamento;
			$t->traducao = $_POST['iregulamento'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Oferta salva com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iCOC->condicao('condicao->alterar.OfertaColetiva', true);

$iCOC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarOfertasColetivas");

$iCOC->trocar("titulo", 			$_POST['titulo']);
$iCOC->trocar("subTitulo", 			$_POST['subTitulo']);

$lEOC = new ListaEmpresasOfertaColetiva;
$iCOC->createRepeticao("repetir->EmpresasOfertaColetiva");
while($eOC = $lEOC->listar("ASC", ListaEmpresasOfertaColetiva::NOME)){
	
	$iCOC->repetir();
	$iCOC->enterRepeticao()->trocar('id.EmpresaOfertaColetiva', $eOC->getId());
	$iCOC->enterRepeticao()->trocar('nome.EmpresaOfertaColetiva', $eOC->nome);
	
}

$iCOC->trocar("empresa", 		$_POST['empresa']);
$iCOC->trocar("url", 			$_POST['url']);
$iCOC->trocar("valorOriginal",	$_POST['valorOriginal']);
$iCOC->trocar("desconto", 		$_POST['desconto']);
$iCOC->trocar("economia", 		$_POST['economia']);
$iCOC->trocar("valor",			$_POST['valor']);
$iCOC->trocar("quantidade",		$_POST['quantidade']);
$iCOC->trocar("comprasMinima",	$_POST['comprasMinima']);
$iCOC->trocar("comprasMaxima",	$_POST['comprasMaxima']);
$iCOC->trocar("destaques", 		$_POST['destaques']);
$iCOC->trocar("regulamento", 	$_POST['regulamento']);

$iCOC->trocar("dataInicio", 	$_POST['dataInicio']);
$iCOC->trocar("dataFim", 		$_POST['dataFim']);
$iCOC->trocar("validadeInicio",	$_POST['validadeInicio']);
$iCOC->trocar("validadeFim", 	$_POST['validadeFim']);


$iCOC->createRepeticao("repetir->ProdutoCategorias.OfertaColetiva");
$iCOC->createRepeticao("repetir->Imagens.OfertaColetiva");

$sub 	= "repetir->titulo.OfertasColetivas.Idiomas";
$sub2 	= "repetir->subTitulo.OfertasColetivas.Idiomas";
$sub3 	= "repetir->destaques.OfertasColetivas.Idiomas";
$sub4	= "repetir->regulamento.OfertasColetivas.Idiomas";
$iCOC->createRepeticao($sub);
$iCOC->createRepeticao($sub2);
$iCOC->createRepeticao($sub3);
$iCOC->createRepeticao($sub4);
while($i = $lI->listar()){
	
	$iCOC->repetir($sub);
	$iCOC->repetir($sub2);
	$iCOC->repetir($sub3);
	$iCOC->repetir($sub4);
	
	$iCOC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCOC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCOC->enterRepeticao($sub)->trocar("titulo.OfertaColetiva.Idioma", $_POST['ititulo'][$i->getId()]);
	
	$iCOC->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iCOC->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCOC->enterRepeticao($sub2)->trocar("subTitulo.OfertaColetiva.Idioma", $_POST['isubTitulo'][$i->getId()]);
	
	$iCOC->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iCOC->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCOC->enterRepeticao($sub3)->trocar("destaques.OfertaColetiva.Idioma", $_POST['idestaques'][$i->getId()]);
		
	$iCOC->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iCOC->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCOC->enterRepeticao($sub3)->trocar("regulamento.OfertaColetiva.Idioma", $_POST['iregulamento'][$i->getId()]);
	
}

$iCOC->createJavaScript();
$javaScript .= $iCOC->javaScript->concluir();

$includePagina = $iCOC->concluir();

?>