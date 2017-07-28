<?php

importar("LojaVirtual.Produtos.Lista.ListaOfertasColetivas");

$tituloPagina = 'Produtos > Ofertas Coletivas > Ofertas';

$iLOC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/listarOfertasColetivas.html"));

$iLOC->trocar("linkDeletar.OfertaColetiva", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLOC->trocar("linkBuscar.OfertaColetiva", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lP = new ListaOfertasColetivas;
	$lP->condicoes('', $_GET['deletar'], ListaOfertasColetivas::ID);
	
	if($lP->getTotal() > 0){
		
		try{
			$lP->deletar($lP->listar());
			$javaScript .= Aviso::criar("Oferta removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lP = new ListaOfertasColetivas;
$iLOC->createRepeticao("repetir->OfertasColetivas");

if(!empty($_GET['busca']))
     $lP->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iLOC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iLOC->trocar("linkCadastrar.OfertaColetiva", "?p=".$_GET['p']."&a=cadastrarOfertaColetiva");

while($p = $lP->listar("ASC", ListaOfertasColetivas::DATAINICIO)){
	  
	   $iLOC->repetir();
	   
	   $iLOC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lP->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLOC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLOC->enterRepeticao()->trocar("id.OfertaColetiva", $p->getId());
	   $iLOC->enterRepeticao()->trocar("titulo.OfertaColetiva", $p->titulo);
	   $iLOC->enterRepeticao()->trocar("linkVisualizar.OfertaColetiva", "?p=".$_GET['p']."&a=listarOfertasColetivas&oferta=".$p->getId());
	   $iLOC->enterRepeticao()->trocar("linkAlterar.OfertaColetiva", "?p=".$_GET['p']."&a=alterarOfertaColetiva&oferta=".$p->getId());
	   
	   $iLOC->enterRepeticao()->condicao("condicaoVisualizar", $p->tipo == 1);
	 
}

$botoes = $iLOC->cutParte('botoes');

$includePagina = $iLOC->concluir();

?>