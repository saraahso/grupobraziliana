<?php

importar("Geral.Lista.ListaUsuarios");

$tituloPagina = 'Configurações > Usuários > Cadastrar';

$iTAU = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/usuario.html"));

$iTAU->condicao('condicao->1.Nivel', $_SESSION['nivel'] == 1);

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";
	elseif(empty($_POST['login']))
	    $erro = "<b>Login</b> não preenchido!<br><br>";
	elseif($_POST['senha'] != $_POST['senha2'])
	    $erro = "<b>Senha</b> está diferente de sua confirmação!<br><br>";

	if(empty($erro)){
		
		$lU = new ListaUsuarios;
		$lU->condicoes('', $_GET['id'], ListaUsuarios::ID);
		$u = $lU->listar();
					
		$u->nome		= $_POST['nome'];		
		$u->login		= $_POST['login'];
		$u->nivel		= $_POST['nivel'];		
		$u->ensino		= $_POST['ensino'];
		$u->texto		= $_POST['texto'];	
		
		if(!empty($_POST['senha']))
			$u->senha		= $_POST['senha'];	
				
		if(!empty($_FILES['imagem']['name']))
			$u->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lU->alterar($u);
				
	    $javaScript .= Aviso::criar("Usuário salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lU = new ListaUsuarios;
$u = $lU->condicoes('', $_GET['id'], ListaUsuarios::ID)->listar();

$iTAU->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarUsuarios");

$iTAU->trocar("nome", 			$u->nome);
$iTAU->trocar("login",			$u->login);
$iTAU->trocar("nivel", 			$u->nivel);
$iTAU->trocar("ensino", 		$u->ensino);
$iTAU->trocar("texto", 			$u->texto);

if($u->getImagem()->nome != '')
	$iTAU->trocar("imagem", 		$u->getImagem()->showHTML(200, 200));

$iTAU->createJavaScript();
$javaScript .= $iTAU->javaScript->concluir();

$includePagina = $iTAU->concluir();

?>