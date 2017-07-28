<?php

importar("Geral.Lista.ListaUsuarios");

$tituloPagina = 'Configurações > Usuários';

$iLI = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/listarUsuarios.html"));

$lU = new ListaUsuarios;

if(!empty($_GET['deletar'])){
	
	$lU = new ListaUsuarios;
	$i = $lU->condicoes('', $_GET['deletar'], ListaUsuarios::ID)->listar();
	
	if($lU->getTotal() > 0){
	
		$lU->deletar($i);
		
		$javaScript .= Aviso::criar('Usuário deletado com sucesso!');
	
	}else
		$javaScript .= Aviso::criar('Erro ao remover usuário!');	
		
	$lU->resetCondicoes();
	$lU->setParametros(0);	
	
}

$iLI->trocar("linkCadastrarUsuario", "?p=".$_GET['p']."&a=cadastrarUsuario");
$iLI->trocar("linkDeletar.Usuario", "?p=".$_GET['p']."&a=".$_GET['a']."&");

$iLI->trocar("linkVoltar.Usuario", "?p=".$_GET['p']."&a=configuracoes");

$iLI->createRepeticao('repetir->Usuarios');
while($u = $lU->listar()){
	
	$iLI->repetir();
	$iLI->enterRepeticao()->trocar('nome.Usuario', $u->nome);
	$iLI->enterRepeticao()->trocar('id.Usuario', $u->getId());
	
	$iLI->enterRepeticao()->trocar('linkAlterar.Usuario', "?p=".$_GET['p']."&a=alterarUsuario&id=".$u->getId());
	
}

$iLI->createJavaScript();
$javaScript .= $iLI->javaScript->concluir();

$botoes = $iLI->cutParte('botoes');

$includePagina = $iLI->concluir();

?>