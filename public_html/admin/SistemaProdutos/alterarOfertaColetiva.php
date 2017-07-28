<?php

importar("Geral.Lista.ListaImagens");
importar("LojaVirtual.Produtos.Lista.ListaOfertasColetivas");

$tituloPagina = 'Produtos > Ofertas Coletivas > Oferta > Alterar';

$iAOC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/ofertaColetiva.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Título</b> não preenchido!<br><br>";
	if(empty($_POST['url']))
	    $erro = "<b>URL</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lOC = new ListaOfertasColetivas;
		$lOC->condicoes('', $_GET['oferta'], ListaOfertasColetivas::ID);
		$p = $lOC->listar();
		
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
		
		$lOC->alterar($p);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaOfertasColetivas::TITULO, $lOC->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaOfertasColetivas::TITULO);
				$t->setTabelaConteudo($lOC->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaOfertasColetivas::SUBTITULO, $lOC->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->subTitulo;
				$t->traducao = $_POST['isubTitulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->subTitulo;
				$t->traducao = $_POST['isubTitulo'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaOfertasColetivas::SUBTITULO);
				$t->setTabelaConteudo($lOC->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaOfertasColetivas::DESTAQUES, $lOC->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->destaques;
				$t->traducao = $_POST['idestaques'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->destaques;
				$t->traducao = $_POST['idestaques'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaOfertasColetivas::DESTAQUES);
				$t->setTabelaConteudo($lOC->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaOfertasColetivas::REGULAMENTO, $lOC->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->regulamento;
				$t->traducao = $_POST['iregulamento'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->regulamento;
				$t->traducao = $_POST['iregulamento'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaOfertasColetivas::REGULAMENTO);
				$t->setTabelaConteudo($lOC->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$con = BDConexao::__Abrir();
		$con->deletar(Sistema::$BDPrefixo."relacionamento_ofertascoletivas_categorias", "WHERE ofertacoletiva = '".$p->getId()."'");
		
		$lPC = new ListaProdutoCategorias;
		
		if(!empty($_POST['categoriasSelecionadas'])){
		
			foreach($_POST['categoriasSelecionadas'] as $valor){
				
				$lPC->condicoes('', $valor, ListaProdutoCategorias::ID);
				
				if($lPC->getTotal() > 0){
					
					$pC = $lPC->listar();
					
					$p->addCategoria($pC);
					
				}
				
			}
		
		}
		
	    $javaScript .= Aviso::criar("Oferta salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lOC = new ListaOfertasColetivas;
$p = $lOC->condicoes('', $_GET['oferta'], ListaOfertasColetivas::ID)->listar();

if(isset($_GET['uploadFlash'])){
	
	$img = new Imagem;
	$img->setSessao($lOC->getTabela(), $p->getId());
	$img->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['Filedata'])));
	
	$lIM = new ListaImagens;
	$lIM->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataOfertasColetivas;
	$lIM->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataOfertasColetivas;
	$lIM->inserir($img);
	
}

$iAOC->condicao('condicao->alterar.OfertaColetiva', false);

$iAOC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarOfertasColetivas");

$iAOC->trocar("titulo", 			$p->titulo);
$iAOC->trocar("subTitulo", 			$p->subTitulo);

$lEOC = new ListaEmpresasOfertaColetiva;
$iAOC->createRepeticao("repetir->EmpresasOfertaColetiva");
while($eOC = $lEOC->listar("ASC", ListaEmpresasOfertaColetiva::NOME)){
	
	$iAOC->repetir();
	$iAOC->enterRepeticao()->trocar('id.EmpresaOfertaColetiva', $eOC->getId());
	$iAOC->enterRepeticao()->trocar('nome.EmpresaOfertaColetiva', $eOC->nome);
	
}

$iAOC->trocar("empresa", 			$p->getEmpresa()->getId());
$iAOC->trocar("url", 				$p->getURL()->url);
$iAOC->trocar("valorOriginal",		$p->valorOriginal->formatar());
$iAOC->trocar("desconto", 			$p->desconto->formatar());
$iAOC->trocar("economia", 			$p->economia->formatar());
$iAOC->trocar("valor",				$p->valor->formatar());
$iAOC->trocar("quantidade",			$p->quantidade);
$iAOC->trocar("comprasMinima",		$p->comprasMinima);
$iAOC->trocar("comprasMaxima",		$p->comprasMaxima);
$iAOC->trocar("comprasEfetuadas",	$p->comprasEfetuadas);
$iAOC->trocar("destaques", 			$p->destaques);
$iAOC->trocar("regulamento", 		$p->regulamento);

$iAOC->trocar("dataInicio", 		$p->getDataInicio()->mostrar("d/m/Y H:i"));
$iAOC->trocar("dataFim", 			$p->getDataFim()->mostrar("d/m/Y H:i"));
$iAOC->trocar("validadeInicio", 	$p->getValidadeInicio()->mostrar("d/m/Y H:i"));
$iAOC->trocar("validadeFim", 		$p->getValidadeFim()->mostrar("d/m/Y H:i"));

$iAOC->trocar('uploadCaminhoURL', Sistema::$adminCaminhoURL."?p=".$_GET['p']."&a=".$_GET['a']."&oferta=".$p->getId()."&uploadFlash");

$iAOC->createRepeticao("repetir->ProdutoCategorias.OfertaColetiva");
while($pC = $p->getCategorias()->listar()){
	
	$iAOC->repetir();
	$iAOC->enterRepeticao()->trocar('id.ProdutoCategoria.OfertaColetiva', $pC->getId());
	$iAOC->enterRepeticao()->trocar('navegador.ProdutoCategoria.OfertaColetiva', $pC->getNavegador());
	
}

$lI 	= new ListaIdiomas;
$sub 	= "repetir->titulo.OfertasColetivas.Idiomas";
$sub2 	= "repetir->subTitulo.OfertasColetivas.Idiomas";
$sub3 	= "repetir->destaques.OfertasColetivas.Idiomas";
$sub4	= "repetir->regulamento.OfertasColetivas.Idiomas";
$iAOC->createRepeticao($sub);
$iAOC->createRepeticao($sub2);
$iAOC->createRepeticao($sub3);
$iAOC->createRepeticao($sub4);
while($i = $lI->listar()){
	
	$iAOC->repetir($sub);
	$iAOC->repetir($sub2);
	$iAOC->repetir($sub3);
	$iAOC->repetir($sub4);
	
	$iAOC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAOC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAOC->enterRepeticao($sub)->trocar("titulo.OfertaColetiva.Idioma", $i->getTraducaoById(ListaEmpresasOfertaColetiva::TITULO, $lOC->getTabela(), $p->getId())->traducao);
	
	$iAOC->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iAOC->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iAOC->enterRepeticao($sub2)->trocar("subTitulo.OfertaColetiva.Idioma", $i->getTraducaoById(ListaEmpresasOfertaColetiva::SUBTITULO, $lOC->getTabela(), $p->getId())->traducao);
	
	$iAOC->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iAOC->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	$iAOC->enterRepeticao($sub3)->trocar("destaques.OfertaColetiva.Idioma", $i->getTraducaoById(ListaEmpresasOfertaColetiva::DESTAQUES, $lOC->getTabela(), $p->getId())->traducao);
	
	$iAOC->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iAOC->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	$iAOC->enterRepeticao($sub3)->trocar("regulamento.OfertaColetiva.Idioma", $i->getTraducaoById(ListaEmpresasOfertaColetiva::REGULAMENTO, $lOC->getTabela(), $p->getId())->traducao);
	
}

$iAOC->trocar("linkDeletar.Imagem.OfertaColetiva", "?p=".$_GET['p']."&a=".$_GET['a']."&oferta=".$p->getId()."&deletar&");
$iAOC->trocar("linkPrincipal.Imagem.OfertaColetiva", "?p=".$_GET['p']."&a=".$_GET['a']."&oferta=".$p->getId()."&principal&imagem={id.Imagem.OfertaColetiva}");

if(isset($_GET['deletar']) && !empty($_GET['imagem'])){
	
	$lI 				= new ListaImagens;
	$lI->caminhoEscrita	= Sistema::$caminhoDiretorio.Sistema::$caminhoDataOfertasColetivas;
	$lI->caminhoLeitura	= $lI->caminhoEscrita;
	$lI->condicoes('', $_GET['imagem'], ListaImagens::ID);
	
	if($lI->getTotal() > 0){
		
		$lI->deletar($lI->listar());
		
		$javaScript .= Aviso::criar("Imagem removida com sucesso!");
		
	}
		
}elseif(isset($_GET['principal']) && !empty($_GET['imagem'])){
	
	$lI 				= new ListaImagens;
	$lI->caminhoEscrita	= Sistema::$caminhoDataOfertasColetivas;
	$lI->caminhoLeitura	= $lI->caminhoEscrita;
	
	while($i = $lI->listar()){
		$i->destaque = false;
		$lI->alterar($i);	
	}
	
	$lI->condicoes('', $_GET['imagem'], ListaImagens::ID);
	
	if($lI->getTotal() > 0){
		
		$img 			= $lI->listar();
		$img->destaque 	= true;
		
		$lI->alterar($img);
		
		$javaScript .= Aviso::criar("Imagem salva com sucesso!");
		
	}
		
}

$iAOC->createRepeticao("repetir->Imagens.OfertaColetiva");
while($img = $p->getImagens()->listar("DESC", ListaImagens::DESTAQUE)){
	
	$iAOC->repetir();
	if($img->getImage()->nome != '')
		$iAOC->enterRepeticao()->trocar("imagem.Imagem.OfertaColetiva", $img->getImage()->showHTML(150, 150));
	$iAOC->enterRepeticao()->trocar("id.Imagem.OfertaColetiva", $img->getId());
	
	$iAOC->enterRepeticao()->condicao('condicao->principal.Imagem.OfertaColetiva', !$img->destaque);
	
}


$iAOC->createJavaScript();
$javaScript .= $iAOC->javaScript->concluir();

$includePagina = $iAOC->concluir();

?>