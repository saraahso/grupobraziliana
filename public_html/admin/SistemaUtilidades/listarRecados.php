<?php

importar("Utilidades.Lista.ListaRecados");

$tituloPagina = 'Utilidades > Mural de Recados';

$ILR = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarRecados.html"));

$ILR->trocar("linkDeletar.Recado", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$ILR->trocar("linkBuscar.Recado", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaRecados;
	$lT->condicoes('', $_GET['deletar'], ListaRecados::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Recado removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaRecados;
$ILR->createRepeticao("repetir->Recados");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$ILR->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$ILR->trocar("linkCadastrar.Recado", "?p=".$_GET['p']."&a=cadastrarRecado");

while($eve = $lT->listar("DESC", ListaRecados::DATA)){
	  
	   $ILR->repetir();
	   
	   $ILR->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $ILR->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $ILR->enterRepeticao()->trocar("id.Recado", $eve->getId());
	   $ILR->enterRepeticao()->trocar("nome.Recado", $eve->nome);
	   $ILR->enterRepeticao()->trocar("situacao.Recado", $eve->getSituacao() ? "Liberado" : "Trancado");
	   
	   $ILR->enterRepeticao()->trocar("linkAlterar.Recado", "?p=".$_GET['p']."&a=alterarRecado&recado=".$eve->getId());
	   
	   $ILR->enterRepeticao()->condicao("condicaoVisualizar", $eve->tipo == 1);
	 
}

$ILR->trocar("linkVoltar", "?p=".$_GET['p']."&a=utilidades");

$botoes = $ILR->cutParte('botoes');

$includePagina = $ILR->concluir();

?>