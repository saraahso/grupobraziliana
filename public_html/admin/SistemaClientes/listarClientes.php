<?php

importar("TemTudoAqui.Usuarios.Lista.ListaPessoas");

$tituloPagina = 'Clientes';

$iTLCL = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaClientes/listarClientes.html"));

$iTLCL->trocar("linkDeletar.Cliente", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLCL->trocar("linkBuscar.Cliente", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lP = new ListaPessoas;
	$lP->condicoes('', $_GET['deletar'], ListaPessoas::ID);
	
	if($lP->getTotal() > 0){
		$lP->deletar($lP->listar());
		$javaScript .= Aviso::criar("Cliente deletado com sucesso!");
	}
	
}

$lP = new ListaPessoas;
$lP->condicoes($aP);
$iTLCL->createRepeticao("repetir->Clientes");

if(!empty($_GET['busca'])){
     $lP->condicoes('', "", '', '', "SELECT c.* FROM ".Sistema::$BDPrefixo."pessoas c, ".Sistema::$BDPrefixo."enderecos e WHERE ((c.nome LIKE '%".$_GET['busca']."%' OR c.email LIKE '%".$_GET['busca']."%') OR ((e.estado LIKE '%".$_GET['busca']."%' OR e.cidade LIKE '%".$_GET['busca']."%') AND c.id = e.ligacao))".($_SESSION['nivel'] == 3 ? " AND c.vendedor = '".$_SESSION['idUsuario']."'" : "")." GROUP BY c.id");
}elseif($_SESSION['nivel'] == 3){
	$lP->condicoes(array(1 => array("campo" => ListaPessoas::VENDEDOR, "valor" => $_SESSION['idUsuario'])));
}

$iTLCL->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTLCL->trocar("linkCadastrar.Cliente", "?p=".$_GET['p']."&a=cadastrarCliente");
$iTLCL->trocar('total.ListaClientes', $lP->getTotal());

$num = 40;
$primeiro = $_GET['pag']*$num;
$total = $lP->getTotal();
$max = ceil($total/$num);
$lP->setParametros($primeiro)->setParametros($num+$primeiro, 'limite');

while($p = $lP->listar("ASC", ListaPessoas::NOME)){
	  
	   $iTLCL->repetir();
	   
	   $iTLCL->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lP->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLCL->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $tel = $p->getTelefone()->listar();
	   
	   $iTLCL->enterRepeticao()->trocar("id.Cliente", $p->getId());
	   $iTLCL->enterRepeticao()->trocar("nome.Cliente", $p->nome);
	   $iTLCL->enterRepeticao()->trocar("telefone.Cliente", "(".$tel->ddd.") ".$tel->telefone." ".$tel->ramal);
	   $iTLCL->enterRepeticao()->trocar("email.Cliente", $p->getEmail()->listar()->email);
	   
	   $iTLCL->enterRepeticao()->trocar("linkVisualizar.Cliente", "?p=".$_GET['p']."&a=listarClientes&cliente=".$p->getId());
	   $iTLCL->enterRepeticao()->trocar("linkAlterar.Cliente", "?p=".$_GET['p']."&a=alterarCliente&cliente=".$p->getId());
	   
	   $iTLCL->enterRepeticao()->condicao("condicaoVisualizar", $p->tipo == 1);
	 
}

$iTLCL->createRepeticao("repetir->Paginacao");
for($i = 0; $i < $max; $i++){

	$iTLCL->repetir();
	$iTLCL->enterRepeticao()->trocar("numero.Paginacao", $i+1);
	$iTLCL->enterRepeticao()->trocar("linkVisualizar.Paginacao", Sistema::$adminCaminhoURL."?p=SistemaClientes&a=listarClientes&pag=".$i."&busca=".$_GET['busca']);
	$iTLCL->enterRepeticao()->condicao("condicao->atual.Paginacao", !($i == $_GET['pag']));
	
}

$botoes = $iTLCL->cutParte('botoes');

$includePagina = $iTLCL->concluir();

?>