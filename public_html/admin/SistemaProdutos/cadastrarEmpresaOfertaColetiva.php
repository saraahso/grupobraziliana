<?php

importar("LojaVirtual.Produtos.Lista.ListaEmpresasOfertaColetiva");
importar("Utils.Dados.Strings");

$tituloPagina = 'Produtos > Ofertas Coletivas > Empresas > Cadastrar';

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
		
		
		$cl 				= new EmpresaOfertaColetiva;
		$cl->razaoSocial	= $_POST['razaoSocial'];
		$cl->getURL()->url  = $_POST['usuario'];
		$cl->getTexto()->titulo = 'Texto sobre '.$_POST['nome'];
		$cl->getTexto()->getURL()->url = 'texto-sobre-'.$_POST['usuario'];
		
		$cl->cnpj			= eregi_replace('\.', '', eregi_replace('-', '', $_POST['cnpj']));
		
		$cl->nome 			= $_POST['nome'];
		$cl->usuario		= $_POST['usuario'];
		$cl->senha			= $_POST['senha'];
		
		if(!empty($_FILES['imagem']['name']))
			$cl->setFoto(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lCL					= new ListaEmpresasOfertaColetiva;
		$lCL->inserir($cl);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Empresa salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTCL->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarEmpresasOfertaColetiva");
$iTCL->condicao('condicao->alterar.Cliente', true);
$_POST['pessoa'] = 'juridica';
$iTCL->trocar("nome", $_POST['nome']);
$iTCL->trocar("usuario", $_POST['usuario']);
$iTCL->trocar("senha", $_POST['senha']);
$iTCL->trocar("pessoa", $_POST['pessoa']);
$iTCL->trocar("rg", $_POST['rg']);
$iTCL->trocar("cpf", $_POST['cpf']);
$iTCL->trocar("sexo", $_POST['sexo']);
$iTCL->trocar("dataNasc", $_POST['dataNasc']);
$iTCL->trocar("razaoSocial", $_POST['razaoSocial']);
$iTCL->trocar("cnpj", $_POST['cnpj']);

if(empty($_POST))
	$iTCL->condicao('condicao->POST', false);
else
	$iTCL->condicao('condicao->POST', true);

$iTCL->createJavaScript();
$javaScript .= $iTCL->javaScript->concluir();

$includePagina = $iTCL->concluir();

?>