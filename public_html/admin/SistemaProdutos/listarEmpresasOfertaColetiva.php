<?php

importar("LojaVirtual.Produtos.Lista.ListaEmpresasOfertaColetiva");

$tituloPagina = 'Produtos > Ofertas Coletivas > Empresas';

$iTLCL = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaClientes/listarClientes.html"));

$iTLCL->trocar("linkDeletar.Cliente", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLCL->trocar("linkBuscar.Cliente", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lP = new ListaEmpresasOfertaColetiva;
	$lP->condicoes('', $_GET['deletar'], ListaEmpresasOfertaColetiva::ID);
	
	if($lP->getTotal() > 0){
		$lP->deletar($lP->listar());
		$javaScript .= Aviso::criar("Empresa deletada com sucesso!");
	}
	
}

$lP = new ListaEmpresasOfertaColetiva;
$iTLCL->createRepeticao("repetir->Clientes");

if(!empty($_GET['busca']))
     $lP->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTLCL->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTLCL->trocar("linkCadastrar.Cliente", "?p=".$_GET['p']."&a=cadastrarEmpresaOfertaColetiva");

$lP->condicoes($aP);
while($p = $lP->listar("DESC", ListaEmpresasOfertaColetiva::NOME)){
	  
	   $iTLCL->repetir();
	   
	   $iTLCL->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lP->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLCL->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $tel = $p->getTelefone()->listar();
	   
	   $iTLCL->enterRepeticao()->trocar("id.Cliente", $p->getId());
	   $iTLCL->enterRepeticao()->trocar("nome.Cliente", $p->nome);
	   $iTLCL->enterRepeticao()->trocar("telefone.Cliente", "(".$tel->ddd.") ".$tel->telefone." ".$tel->ramal);
	   $iTLCL->enterRepeticao()->trocar("email.Cliente", $p->getEmail()->listar()->email);
	   
	   $iTLCL->enterRepeticao()->trocar("linkVisualizar.Cliente", "?p=".$_GET['p']."&a=listarEmpresaOfertaColetiva&cliente=".$p->getId());
	   $iTLCL->enterRepeticao()->trocar("linkAlterar.Cliente", "?p=".$_GET['p']."&a=alterarEmpresaOfertaColetiva&cliente=".$p->getId());
	   
	   $iTLCL->enterRepeticao()->condicao("condicaoVisualizar", $p->tipo == 1);
	 
}

$botoes = $iTLCL->cutParte('botoes');

$includePagina = $iTLCL->concluir();

?>