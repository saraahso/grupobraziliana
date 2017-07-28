<?php

importar("Geral.Lista.ListaUsuarios");

$tituloPagina = 'Configurações > Usuários > Cadastrar';

$iTCU = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/usuario.html"));

$iTCU->condicao('condicao->1.Nivel', $_SESSION['nivel'] == 1);

if(!empty($_POST)){
	
	$erro = '';
		
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";
	elseif(empty($_POST['login']))
	    $erro = "<b>Login</b> não preenchido!<br><br>";
	elseif(empty($_POST['senha']))
	    $erro = "<b>Senha</b> não preenchido!<br><br>";
	elseif($_POST['senha'] != $_POST['senha2'])
	    $erro = "<b>Senha</b> está diferente de sua confirmação!<br><br>";

	if(empty($erro)){
		
		try{
			
			$u 				= new Usuario;
			
			$u->nome		= $_POST['nome'];		
			$u->login		= $_POST['login'];
			$u->nivel		= $_POST['nivel'];		
			$u->ensino		= $_POST['ensino'];
			$u->texto		= $_POST['texto'];	
			
			if(!empty($_POST['senha']))
				$u->senha		= $_POST['senha'];	
					
			if(!empty($_FILES['imagem']['name']))
				$u->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
			
			$lU				= new ListaUsuarios;
			$lU->inserir($u);
			
			$_POST = '';
			
			//header("Location: ".Sistema::$adminCaminhoURL."?p=SistemaUtilidades&a=alterarBanner&banner=".$u->getId());
			$javaScript .= Aviso::criar("Usuário salvo com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTCU->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarUsuarios");

$iTCU->trocar("nome", 		$_POST['nome']);
$iTCU->trocar("login",		$_POST['login']);
$iTCU->trocar("ensino", 	$_POST['ensino']);
$iTCU->trocar("nivel",		$_POST['nivel']);
$iTCU->trocar("texto", 		$_POST['texto']);

$iTCU->createJavaScript();
$javaScript .= $iTCU->javaScript->concluir();

$includePagina = $iTCU->concluir();

?>