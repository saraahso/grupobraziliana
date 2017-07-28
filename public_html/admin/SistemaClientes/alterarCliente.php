<?php

importar("TemTudoAqui.Usuarios.Lista.ListaPessoas");
importar("LojaVirtual.Pedidos.Lista.ListaPedidos");
importar("Utils.Dados.JSON");

$clituloPagina = 'Clientes > Alterar';

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
		
		$lCL = new ListaPessoas;
		$lCL->condicoes('', $_GET['cliente'], ListaPessoas::ID);
		$cl = $lCL->listar();
		
		if($_POST['pessoa'] == 'fisica'){
	    	$cl 				= new PessoaFisica($cl->getId());
			$cl->rg				= $_POST['rg'];
			$cl->cpf			= eregi_replace('\.', '', eregi_replace('-', '', $_POST['cpf']));
			$cl->sexo			= $_POST['sexo'];
			$cl->setDataNasc(new DataHora($_POST['dataNasc']));
		}elseif($_POST['pessoa'] == 'juridica'){
			$cl 				= new PessoaJuridica($cl->getId());
			$cl->razaoSocial	= $_POST['razaoSocial'];
			$cl->cnpj			= eregi_replace('/', '', eregi_replace('\.', '', eregi_replace('-', '', $_POST['cnpj'])));
		}
		
		$cl->nome 			= $_POST['nome'];
		$cl->emailPrimario	= $_POST['emailPrimario'];
		$cl->sobreNome 		= $_POST['sobreNome'];
		$cl->usuario		= $_POST['usuario'];
		$cl->senha			= $_POST['senha'];
		$cl->atacadista		= $_POST['atacadista'] == ListaPessoas::VALOR_ATACADISTA_TRUE ? true : false;
		
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
		

		
		$javaScript .= Aviso::criar("Cliente salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lCL = new ListaPessoas;
$cl = $lCL->condicoes('', $_GET['cliente'], ListaPessoas::ID)->listar();

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
$iTCL->trocar("sobreNome", $cl->sobreNome);
$iTCL->trocar("usuario", $cl->usuario);
$iTCL->trocar("senha", $cl->senha);
$iTCL->trocar("pessoa", (is_a($cl, "PessoaFisica") ? 'fisica' : 'juridica' ));
$iTCL->trocar("rg", $cl->rg);
$iTCL->trocar("cpf", $cl->cpf);
$iTCL->trocar("sexo", $cl->sexo);
$iTCL->trocar("atacadista", $cl->atacadista);
$iTCL->trocar("dataNasc", is_a($cl, "PessoaFisica") ? $cl->getDataNasc()->mostrar() : '');
$iTCL->trocar("razaoSocial", $cl->razaoSocial);
$iTCL->trocar("cnpj", $cl->cnpj);
$iTCL->trocar("atacadista", $cl->atacadista ? 1 : 0);
$iTCL->trocar("email", $cl->emailPrimario);
$iTCL->trocar("origemCadastro", $cl->origemCadastro);

$iTCL->condicao('condicao->POST', true);
$iTCL->condicao('condicao->alterar.Cliente', false);

//Endereço

$lPA = new ListaPaises;
$iTCL->createRepeticao("repetir->Pais");
while($pA = $lPA->listar("ASC", ListaPaises::NOME)){
	
	$iTCL->repetir();
	$iTCL->enterRepeticao()->trocar("id.Pais", $pA->getId());
	$iTCL->enterRepeticao()->trocar("nome.Pais", $pA->nome);
	if($pA->getId() == $pais)
		$iTCL->enterRepeticao()->trocar("selected.Pais", 'selected');
	
}


$lE = new ListaEstados;
$iTCL->createRepeticao("repetir->Estado");
if(!empty($_GET['pais'])){
	
	$lE->condicoes('', $_GET['pais'], ListaEstados::PAIS);
	
	if(isset($_GET['json'])){
	
		$a = array();
		
		while($e = $lE->listar("ASC", ListaEstados::NOME))
			$a[] = array('id' => $e->getId(), 'nome' => $e->nome, 'uf' => $e->uf);
		
		echo JSON::_Encode($a, true);
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


$lC = new ListaCidades;
$iTCL->createRepeticao("repetir->Cidade");
if(!empty($_GET['estado'])){
	
	$lC->condicoes('', $_GET['estado'], ListaCidades::ESTADO);
	
	if(isset($_GET['json'])){
	
		$a = array();
		
		while($c = $lC->listar("ASC", ListaCidades::NOME))
			$a[] = array('id' => $c->getId(), 'nome' => $c->nome);
		
		echo JSON::_Encode($a);
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
	$iTCL->enterRepeticao()->trocar("cidade.Endereco.Cliente", $end->getCidade()->nome);
	$iTCL->enterRepeticao()->trocar("estado.Endereco.Cliente", $end->getEstado()->nome);
	if($end->getCep())
		$iTCL->enterRepeticao()->trocar("cep.Endereco.Cliente", $end->getCep());
	
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

//Compras

$lP = new ListaPedidos;
$lP->condicoes("", $cl->getId(), ListaPedidos::IDSESSAO);
$iTCL->createRepeticao("repetir->Pedidos");
while($p = $lP->listar("DESC", ListaPedidos::DATA)){
	
	$iTCL->repetir();
	
	$iTCL->enterRepeticao()->trocar("id.Pedido", $p->getId());
	$iTCL->enterRepeticao()->trocar("data.Pedido", $p->getData()->mostrar());
	$iTCL->enterRepeticao()->trocar("status.Pedido", $p->getStatus());
	$iTCL->enterRepeticao()->trocar("moeda", "R$");
	$iTCL->enterRepeticao()->trocar("valor.Endereco.Pedido", $p->getEndereco()->getValor()->moeda());
	$iTCL->enterRepeticao()->trocar("total.Pedido", $p->getValor()->moeda());
	
	$iTCL->enterRepeticao()->createRepeticao("repetir->Itens.Pedido");
	while($pI = $p->getItem()->listar()){
		
		$iTCL->enterRepeticao()->repetir();
			if($pI->getImagens()->getTotal() > 0){
			$img = $pI->getImagens()->listar("DESC", ListaImagens::DESTAQUE);
			$iTCL->enterRepeticao()->enterRepeticao()->trocar("imagem.Item.Pedido", $img->getImage()->showHTML(100, 100));
		}
		$iTCL->enterRepeticao()->enterRepeticao()->trocar('id.Item.Pedido', 		$pI->getId());
		$iTCL->enterRepeticao()->enterRepeticao()->trocar('nome.Item.Pedido', 		$pI->nome);
		if($pI->unidade > 0){
			$iTCL->enterRepeticao()->enterRepeticao()->trocar('unidade.Item.Pedido', 		$pI->unidade);
			$iTCL->enterRepeticao()->enterRepeticao()->trocar('tipoUnidade.Item.Pedido', 	$pI->tipoUnidade);
		}
		
		$iTCL->enterRepeticao()->enterRepeticao()->trocar('descricao.Item.Pedido', 	$pI->descricao);
		$iTCL->enterRepeticao()->enterRepeticao()->trocar('moeda', 				"R$");
		$iTCL->enterRepeticao()->enterRepeticao()->trocar('valor.Item.Pedido', 		$pI->valor->moeda());
		$iTCL->enterRepeticao()->enterRepeticao()->trocar('quantidade.Item.Pedido', $pI->quantidade);
		
		$iTCL->enterRepeticao()->enterRepeticao()->trocar('linkAlterar.Item.Pedido', "?p=SistemaProdutos&a=alterarProduto&produto=".$pI->getId());
		
		
	}
	
}

//

$iTCL->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarClientes");

$iTCL->createJavaScript();
$javaScript .= $iTCL->javaScript->concluir();

$includePagina = $iTCL->concluir();

?>