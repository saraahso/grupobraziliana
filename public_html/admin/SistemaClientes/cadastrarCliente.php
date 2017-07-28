<?php

importar("TemTudoAqui.Usuarios.Lista.ListaPessoas");
importar("Utils.Dados.Strings");

$tituloPagina = 'Clientes > Cadastrar';

$iTCL = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaClientes/cliente.html"));

if(!empty($_POST)){
	
	$erro = '';

    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";
	elseif(empty($_POST['pessoa']))
		$erro = "<b>Pessoa</b> não escolhido!<br><br>";
	elseif(!empty($_POST['cpf']) && $_POST['pessoa'] == 'fisica'){
		if(!Strings::__VerificarCPF($_POST['cpf']))
			$erro = "<b>CPF</b> inválido!<br><br>";
	}elseif(!empty($_POST['cnpj']) && $_POST['pessoa'] == 'juridica'){
		if(!Strings::__VerificarCNPJ($_POST['cnpj']))
			$erro = "<b>CNPJ</b> inválido!<br><br>";
	}
	
	if(empty($erro)){
		
		if($_POST['pessoa'] == 'fisica'){
	    	$cl 			= new PessoaFisica;
			$cl->rg				= $_POST['rg'];
			$cl->cpf			= eregi_replace('\.', '', eregi_replace('-', '', $_POST['cpf']));
			$cl->sexo			= $_POST['sexo'];
			$cl->setDataNasc(new DataHora($_POST['dataNasc']));
		}elseif($_POST['pessoa'] == 'juridica'){
			$cl 			= new PessoaJuridica;
			$cl->razaoSocial	= $_POST['razaoSocial'];
			$cl->cnpj			= eregi_replace('\.', '', eregi_replace('-', '', $_POST['cnpj']));
		}
		
		$cl->nome 			= $_POST['nome'];
		$cl->sobreNome		= $_POST['sobreNome'];
		$cl->usuario		= $_POST['usuario'];
		$cl->senha			= $_POST['senha'];
		$cl->emailPrimario	= $_POST['emailPrimario'];
		$cl->atacadista		= $_POST['atacadista'] == ListaPessoas::VALOR_ATACADISTA_TRUE ? true : false;
		
		if(!empty($_FILES['imagem']['name']))
			$cl->setFoto(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lCL					= new ListaPessoas;
		$lCL->inserir($cl);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Cliente salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTCL->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarClientes");
$iTCL->condicao('condicao->alterar.Cliente', true);

$iTCL->trocar("nome", $_POST['nome']);
$iTCL->trocar("sobreNome", $_POST['sobreNome']);
$iTCL->trocar("usuario", $_POST['usuario']);
$iTCL->trocar("senha", $_POST['senha']);
$iTCL->trocar("pessoa", $_POST['pessoa']);
$iTCL->trocar("rg", $_POST['rg']);
$iTCL->trocar("cpf", $_POST['cpf']);
$iTCL->trocar("sexo", $_POST['sexo']);
$iTCL->trocar("dataNasc", $_POST['dataNasc']);
$iTCL->trocar("razaoSocial", $_POST['razaoSocial']);
$iTCL->trocar("cnpj", $_POST['cnpj']);
$iTCL->trocar("atacadista", $_POST['atacadista']);
$iTCL->trocar("email", $_POST['emailPrimario']);

if(empty($_POST))
	$iTCL->condicao('condicao->POST', false);
else
	$iTCL->condicao('condicao->POST', true);

$iTCL->createJavaScript();
$javaScript .= $iTCL->javaScript->concluir();

$includePagina = $iTCL->concluir();

?>