<?php

importar("LojaVirtual.Produtos.Lista.ListaEmpresasOfertaColetiva");
importar("Utils.Ajax");

$tituloPagina = 'Produtos > Ofertas Coletivas > Empresas > Alterar';

$iTCL = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaClientes/cliente.html"));

$iTCL->trocar('linkDeletar.Cliente', '?p='.$_GET['p'].'&a='.$_GET['a'].'&cliente='.$_GET['cliente'].'&');

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
		
		$lCL = new ListaEmpresasOfertaColetiva;
		$lCL->condicoes('', $_GET['cliente'], ListaEmpresasOfertaColetiva::ID);
		$cl = $lCL->listar();
		
		
		$cl 				= new EmpresaOfertaColetiva($cl->getId());
		$cl->razaoSocial	= $_POST['razaoSocial'];
		$cl->cnpj			= eregi_replace('/', '', eregi_replace('\.', '', eregi_replace('-', '', $_POST['cnpj'])));
		
		$cl->nome 			= $_POST['nome'];
		$cl->usuario		= $_POST['usuario'];
		$cl->senha			= $_POST['senha'];
		
		if(!empty($_FILES['imagem']['name']))
			$cl->setFoto(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lCL->alterar($cl);
		
		if(!empty($_POST['cep'])){
			
			if(!empty($_POST['logradouro']) && !empty($_POST['numero']) && !empty($_POST['bairro']) && !empty($_POST['pais']) && !empty($_POST['estado']) && !empty($_POST['cidade']) && !empty($_POST['cep'])){
				
				$end = new Endereco;
				
				$end->logradouro = $_POST['logradouro'];
				$end->numero = $_POST['numero'];
				$end->complemento = $_POST['complemento'];
				$end->bairro = $_POST['bairro'];
				$end->cidade = $_POST['cidade'];
				$end->estado = $_POST['estado'];
				$end->pais = $_POST['pais'];
				$end->setCEP($_POST['cep']);
				
				$cl->addEndereco($end);
				
			}else
				$javaScript .= Aviso::criar("Dados incompletos do endereço!");
			
		}
		
		if(!empty($_POST['telefone'])){
		
			if(!empty($_POST['descricaoT']) && !empty($_POST['telefone'])){
				
				$tel = new Telefone;
				
				$tel->local = $_POST['descricaoT'];
				$tel->telefone = eregi_replace('-', '', substr($_POST['telefone'], 5, 9));
				$tel->ddd = substr($_POST['telefone'], 1, 2);
				$tel->ramal = $_POST['ramal'];
				
				$cl->addTelefone($tel);
				
			}else
				$javaScript .= Aviso::criar("Dados incompletos do telefone!");
		
		}
		
			if(!empty($_POST['email'])){
		

				$e = new Email;
				
				$e->descricao = $_POST['descricaoE'];
				$e->email = $_POST['email'];
				
				$cl->addEmail($e);
				
			}
		

		
		$javaScript .= Aviso::criar("Empresa salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lCL = new ListaEmpresasOfertaColetiva;
$cl = $lCL->condicoes('', $_GET['cliente'], ListaEmpresasOfertaColetiva::ID)->listar();

if(!empty($_GET['endereco'])){
	
	$lE = new ListaEnderecos;
	$lE->condicoes('', $_GET['endereco'], ListaEnderecos::ID);
	
	if($lE->getTotal() > 0){
		$lE->deletar($lE->listar());
		$javaScript .= Aviso::criar("Endereço deletado com sucesso!");
	}
	
}

if(!empty($_GET['telefone'])){
	
	$lT = new ListaTelefones;
	$lT->condicoes('', $_GET['telefone'], ListaTelefones::ID);
	
	if($lT->getTotal() > 0){
		$lT->deletar($lT->listar());
		$javaScript .= Aviso::criar("Telefone deletado com sucesso!");
	}
	
}

if(!empty($_GET['email'])){
	
	$lE = new ListaEmails;
	$lE->condicoes('', $_GET['email'], ListaEmails::ID);
	
	if($lE->getTotal() > 0){
		$lE->deletar($lE->listar());
		$javaScript .= Aviso::criar("E-mail deletado com sucesso!");
	}
	
}

$pais = $_GET['pais'];

$iTCL->trocar("nome", $cl->nome);
$iTCL->trocar("usuario", $cl->usuario);
$iTCL->trocar("senha", $cl->senha);
$iTCL->trocar("pessoa", 'juridica');
$iTCL->trocar("rg", $cl->rg);
$iTCL->trocar("cpf", $cl->cpf);
$iTCL->trocar("sexo", $cl->sexo);
$iTCL->trocar("dataNasc", '');
$iTCL->trocar("razaoSocial", $cl->razaoSocial);
$iTCL->trocar("cnpj", $cl->cnpj);

if(!empty($cl->getFoto()->nome))
	$iTCL->trocar("imagem", $cl->getFoto()->showHTML(200, 200));

$iTCL->condicao('condicao->POST', true);
$iTCL->condicao('condicao->alterar.Cliente', false);

//Endereço

$con = new Lista("pais");
$iTCL->createRepeticao("repetir->Pais");
while($rs = $con->listar()){
	
	$iTCL->repetir();
	$iTCL->enterRepeticao()->trocar("id.Pais", $rs['id']);
	$iTCL->enterRepeticao()->trocar("nome.Pais", $rs['nome']);
	
	//if($con->getParametros() == 1 && empty($pais))
		//$pais = $rs['id'];
		
	if($rs['id'] == $pais)
		$iTCL->enterRepeticao()->trocar("selected.Pais", 'selected');
	
}


$con = new Lista("estado");
$iTCL->createRepeticao("repetir->Estado");
if(!empty($_GET['pais'])){
	
	$con->condicoes('', $_GET['pais'], "pais");
	
	if(isset($_GET['json'])){
	
		$a = array('lista' => true);
		
		while($rs = $con->listar())
			$a[$con->getParametros()] = array('id' => $rs['id'], 'nome' => $rs['nome'], 'uf' => $rs['uf']);
		
		$ajax = new Ajax;
		echo $ajax->getJSON()->converter($a);
		exit;
		
	}
	
}
/*
while($rs = $con->listar()){
	
	$iTCL->repetir();
	$iTCL->enterRepeticao()->trocar("id.Estado", $rs['id']);
	$iTCL->enterRepeticao()->trocar("nome.Estado", $rs['nome']);
	$iTCL->enterRepeticao()->trocar("uf.Estado", $rs['uf']);
	
	if($rs['id'] == $estado)
		$iTCL->enterRepeticao()->trocar("selected.Estado", 'selected');
	
}
*/


$con = new Lista("cidade");
$iTCL->createRepeticao("repetir->Cidade");
if(!empty($_GET['estado'])){
	
	$con->condicoes('', $_GET['estado'], "estado");
	
	if(isset($_GET['json'])){
	
		$a = array('lista' => true);
		
		while($rs = $con->listar())
			$a[$con->getParametros()] = array('id' => $rs['id'], 'nome' => $rs['nome']);
		
		$ajax = new Ajax;
		echo $ajax->getJSON()->converter($a);
		exit;
		
	}
	
}
/*
while($rs = $con->listar()){
	
	$iTCL->repetir();
	$iTCL->enterRepeticao()->trocar("id.Cidade", $rs['id']);
	$iTCL->enterRepeticao()->trocar("nome.Cidade", $rs['nome']);
	
	if($rs['id'] == $cidade)
		$iTCL->enterRepeticao()->trocar("selected.Cidade", 'selected');
	
}
*/

$iTCL->createRepeticao("repetir->Enderecos.Cliente");
while($end = $cl->getEndereco()->listar()){
	
	$iTCL->repetir();
	$iTCL->enterRepeticao()->trocar("id.Endereco.Cliente", $end->getId());
	$iTCL->enterRepeticao()->trocar("logradouro.Endereco.Cliente", $end->logradouro);
	$iTCL->enterRepeticao()->trocar("complemento.Endereco.Cliente", $end->complemento);
	$iTCL->enterRepeticao()->trocar("numero.Endereco.Cliente", $end->numero);
	$iTCL->enterRepeticao()->trocar("bairro.Endereco.Cliente", $end->bairro);
	
}
//

//Telefones

$iTCL->createRepeticao("repetir->Telefones.Cliente");
while($tel = $cl->getTelefone()->listar()){
	
	$iTCL->repetir();
	$iTCL->enterRepeticao()->trocar("id.Telefone.Cliente", $tel->getId());
	$iTCL->enterRepeticao()->trocar("ddd.Telefone.Cliente", $tel->ddd);
	$iTCL->enterRepeticao()->trocar("numero.Telefone.Cliente", $tel->telefone);
	$iTCL->enterRepeticao()->trocar("descricao.Telefone.Cliente", $tel->local);
	$iTCL->enterRepeticao()->trocar("ramal.Telefone.Cliente", $tel->ramal);
	
}

//

//Emails

$iTCL->createRepeticao("repetir->Emails.Cliente");
while($e = $cl->getEmail()->listar()){
	
	$iTCL->repetir();
	$iTCL->enterRepeticao()->trocar("id.Email.Cliente", $e->getId());
	$iTCL->enterRepeticao()->trocar("email.Email.Cliente", $e->email);
	
}

//

$iTCL->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarEmpresasOfertaColetiva");

$iTCL->createJavaScript();
$javaScript .= $iTCL->javaScript->concluir();

$includePagina = $iTCL->concluir();

?>