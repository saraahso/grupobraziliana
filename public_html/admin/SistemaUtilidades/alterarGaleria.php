<?php

importar("Utilidades.Galerias.Lista.ListaGalerias");

$tituloPagina = 'Utilidades > Galerias > Alterar';

$iTAG = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/galeria.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lG = new ListaGalerias;
		$lG->condicoes('', $_GET['galeria'], ListaGalerias::ID);
		$g = $lG->listar();
		
		$g->titulo 		= $_POST['titulo'];		
		$g->local		= $_POST['local'];
		
		$g->getURL()->setURL($_POST['url'] ? $_POST['url'] : $g->getId()."-".URL::cleanURL(($_POST['titulo'])));
		
		$g->tipo		= $_POST['tipo'];
		$g->ordem		= $_POST['ordem'];
		
		$g->setVideo($_POST['video']);
		$g->setData(new DataHora($_POST['data']));
		$g->descricao 	= $_POST['descricao'];
		
		$lG->alterar($g);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaGalerias::TITULO, $lG->getTabela(), $g->getId());

			if($t->getId()){
				
				$t->conteudo = $g->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $g->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($g->getId());
				$t->setCampoConteudo(ListaGalerias::TITULO);
				$t->setTabelaConteudo($lG->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaGalerias::DESCRICAO, $lG->getTabela(), $g->getId());
			
			if($t->getId()){
				
				$t->conteudo = $g->descricao;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $g->descricao;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$t->setIdConteudo($g->getId());
				$t->setCampoConteudo(ListaGalerias::DESCRICAO);
				$t->setTabelaConteudo($lG->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$con = BDConexao::__Abrir();
		$con->deletar(Sistema::$BDPrefixo."relacionamento_galerias_categorias", "WHERE galeria = '".$g->getId()."'");
		
		$lGC = new ListaGaleriaCategorias;
		
		if(count($_POST['categoriasSelecionadas']) > 0){
		
			for($i = count($_POST['categoriasSelecionadas'])-1; $i >= 0; $i--){
				
				$valor = $_POST['categoriasSelecionadas'][$i];
				
				$lGC->condicoes('', $valor, ListaGaleriaCategorias::ID);
				
				if($lGC->getTotal() > 0){
					
					$gC = $lGC->listar();
					
					$g->addCategoria($gC);
					
				}
				
			}
		
		}
		
		if(!empty($_POST['remover'])){
				
			$lI 				= new ListaImagens;
			$lI->caminhoEscrita	= Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias;
			$lI->caminhoLeitura	= $lI->caminhoEscrita;
			
			foreach($_POST['remover'] as $id){
				
				$lI->condicoes('', $id, ListaImagens::ID);
				
				if($lI->getTotal() > 0){
					
					$img = $lI->listar();
					$lI->deletar($img);
					
				}
				
			}
			
		}
		
		if(!empty($_POST['legenda'])){
				
			$lI 				= new ListaImagens;
			$lI->caminhoEscrita	= Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias;
			$lI->caminhoLeitura	= $lI->caminhoEscrita;
			
			foreach($_POST['legenda'] as $k => $v){
				
				$lI->condicoes('', $k, ListaImagens::ID);
				
				if($lI->getTotal() > 0){
					
					$img = $lI->listar();
					$img->legenda = $v;
					$lI->alterar($img);
					
				}
				
			}
			
		}
		
	    $javaScript .= Aviso::criar("Galeria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lG = new ListaGalerias;
$g = $lG->condicoes('', $_GET['galeria'], ListaGalerias::ID)->listar();

if(isset($_GET['uploadFlash'])){
	
	$con = BDConexao::__Abrir();
	$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."marcadagua");
	$rs = $con->getRegistro();
	
	$img = new Imagem;
	$img->setSessao($lG->getTabela(), $g->getId());
	$arq = Arquivos::__OpenArquivoByTEMP($_FILES['Filedata']);
	//header("Content-type: image/jpeg");
	$img->setImage(new Image($arq));
	$img->getImage()->open();
	//echo $img->getImage()->getImage();exit;
	
	if($rs['galerias']){
	
		if($rs['tipo'] == 1){
			
			if(!empty($rs['texto'])){
				
				$ma = new NewImage(strlen($rs['texto'])*9, 20);
				$ma->writeText($rs['texto']);	
			}
			
		}elseif($rs['tipo'] == 2){
			if(!empty($rs['imagem'])){
				
				$ma = new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataIdiomas.$rs['imagem']));		
				$ma->open();
				//echo $ma->getImage();exit;
			}
		}
		
		if($ma){
			
			if($rs['posicaohorizontal'] == 1){
				$x = 0;
			}elseif($rs['posicaohorizontal'] == 2){
				$x = ($img->getImage()->largura/2)-($ma->largura/2);
			}elseif($rs['posicaohorizontal'] == 3){
				$x = $img->getImage()->largura-$ma->largura;
			}
			
			if($rs['posicaovertical'] == 1){
				$y = 0;
			}elseif($rs['posicaovertical'] == 2){
				$y = ($img->getImage()->altura/2)-($ma->altura/2);
			}elseif($rs['posicaovertical'] == 3){
				$y = $img->getImage()->altura-$ma->altura;
			}
			
			$img->getImage()->combineImage($ma, 0, 0, $x, $y, 50);
			
		}
	
	}
	
	$lIM = new ListaImagens;
	$lIM->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias;
	$lIM->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataGalerias;
	$img->getImage()->redimensionar(1500, 1500);
	$lIM->inserir($img);
	
	exit;
	
}

$iTAG->condicao('condicao->alterar.Galeria', false);

$iTAG->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarGalerias");

$iTAG->trocar("titulo", 		$g->titulo);
$iTAG->trocar("url", 			$g->getURL()->getURL());
$iTAG->trocar("local", 			$g->local);
$iTAG->trocar("tipo", 			$g->tipo);
$iTAG->trocar("data", 			$g->getData()->mostrar("d/m/Y H:i"));
$iTAG->trocar("descricao", 		$g->descricao);
$iTAG->trocar("ordem", 			$g->ordem);

$iTAG->trocar("video", 			$g->getVideo());

$iTAG->trocar('uploadCaminhoURL', Sistema::$adminCaminhoURL."?p=".$_GET['p']."&a=".$_GET['a']."&galeria=".$g->getId()."&uploadFlash");

$iTAG->createRepeticao("repetir->GaleriaCategorias.Galeria");
while($sC = $g->getCategorias()->listar()){
	
	$iTAG->repetir();
	$iTAG->enterRepeticao()->trocar('id.GaleriaCategoria.Galeria', $sC->getId());
	$iTAG->enterRepeticao()->trocar('titulo.GaleriaCategoria.Galeria', $sC->titulo);
	
}

$lI 	= new ListaIdiomas;
$sub 	= "repetir->titulo.Galerias.Idiomas";
$sub2 	= "repetir->descricao.Galerias.Idiomas";

$iTAG->createRepeticao($sub);
$iTAG->createRepeticao($sub2);

while($i = $lI->listar()){
	
	$iTAG->repetir($sub);
	$iTAG->repetir($sub2);

	
	$iTAG->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTAG->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTAG->enterRepeticao($sub)->trocar("titulo.Galeria.Idioma", $i->getTraducaoById(ListaGalerias::TITULO, $lG->getTabela(), $g->getId())->traducao);
	
	$iTAG->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTAG->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iTAG->enterRepeticao($sub2)->trocar("descricao.Galeria.Idioma", $i->getTraducaoById(ListaGalerias::DESCRICAO, $lG->getTabela(), $g->getId())->traducao);
	
}

$iTAG->trocar("linkDeletar.Imagem.Galeria", "?p=".$_GET['p']."&a=".$_GET['a']."&galeria=".$g->getId()."&deletar&");
$iTAG->trocar("linkPrincipal.Imagem.Galeria", "?p=".$_GET['p']."&a=".$_GET['a']."&galeria=".$g->getId()."&principal&imagem={id.Imagem.Galeria}");

if(isset($_GET['deletar']) && !empty($_GET['imagem'])){
	
	$lI 				= new ListaImagens;
	$lI->caminhoEscrita	= Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias;
	$lI->caminhoLeitura	= $lI->caminhoEscrita;
	$lI->condicoes('', $_GET['imagem'], ListaImagens::ID);
	
	if($lI->getTotal() > 0){
		
		$img = $lI->listar();
		$lI->deletar($img);
		
		$javaScript .= Aviso::criar("Imagem removida com sucesso!");
		
	}
		
}elseif(isset($_GET['principal']) && !empty($_GET['imagem'])){
	
	$lI 				= new ListaImagens;
	$lI->caminhoEscrita	= Sistema::$caminhoDataGalerias;
	$lI->caminhoLeitura	= $lI->caminhoEscrita;
	$lI->condicoes('', $_GET['imagem'], ListaImagens::ID);
	
	while($img = $g->getImagens()->listar()){
		$img->destaque = false;
		$lI->alterar($img);
	}
	
	$g->getImagens()->setParametros(0);
	
	if($lI->getTotal() > 0){
		
		$img 			= $lI->listar();
		$img->destaque 	= true;
		
		$lI->alterar($img);
		
		$javaScript .= Aviso::criar("Imagem salva com sucesso!");
		
	}
		
}

$iTAG->createRepeticao("repetir->Imagens.Galeria");
while($img = $g->getImagens()->listar("DESC", ListaImagens::DESTAQUE)){
	
	$iTAG->repetir();
	$iTAG->enterRepeticao()->trocar("imagem.Imagem.Galeria", $img->getImage()->showHTML(150, 150));
	$iTAG->enterRepeticao()->trocar("id.Imagem.Galeria", $img->getId());
	$iTAG->enterRepeticao()->trocar("legenda.Imagem.Galeria", $img->legenda);
	
	$iTAG->enterRepeticao()->condicao('condicao->principal.Imagem.Galeria', !$img->destaque);
	
}

$iTAG->createJavaScript();
$javaScript .= $iTAG->javaScript->concluir();

$includePagina = $iTAG->concluir();

?>