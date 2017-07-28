<?php

importar("TemTudoAqui.Usuarios.Lista.ListaPessoas");
importar("LojaVirtual.Pedidos.PedidoStatus");
importar("Utils.Dados.Arrays");
importar("Utils.Dados.Numero");

if(!empty($_POST)){
		
	$iGR = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/relatorioCliente.html"));
		
	$con = BDConexao::__Abrir();
		
	if(!empty($_POST['data'])){
		
		$d = explode("/", $_POST['data']);

		$order = "ASC";
		$campo = "p.".ListaPessoas::NOME;
		
		if((int)$d[0] > 0)
			$con->executar("SELECT *, (SELECT SUM(pe.valor) as total FROM ".Sistema::$BDPrefixo."pedidos pe WHERE pe.sessao = p.id AND (pe.status = '".PedidoStatus::FECHADO."' OR pe.status = '".PedidoStatus::ENTREGA."')) as valorcompras FROM ".Sistema::$BDPrefixo."pessoas p WHERE p.".ListaPessoas::DATANASC." LIKE '".'%'.$d[1].$d[0]."' ORDER BY ".$campo." ".$order);
		else
			$con->executar("SELECT *, (SELECT SUM(pe.valor) as total FROM ".Sistema::$BDPrefixo."pedidos pe WHERE pe.sessao = p.id AND (pe.status = '".PedidoStatus::FECHADO."' OR pe.status = '".PedidoStatus::ENTREGA."')) as valorcompras FROM ".Sistema::$BDPrefixo."pessoas p WHERE p.".ListaPessoas::DATANASC." REGEXP '([0-9][0-9][0-9][0-9])".$d[1]."([0-9][0-9])' ORDER BY ".$campo." ".$order);
		
	}elseif($_POST['ordenar'] == 1 || $_POST['ordenar'] == 2){
		
		if($_POST['ordenar'] == 1)
			$order = "DESC";
		if($_POST['ordenar'] == 2)
			$order = "ASC";
		
		$campo = 'valorcompras';
		
		if(!empty($_POST['dataInicial']) && !empty($_POST['dataInicial'])){
		
			$dTI = new DataHora($_POST['dataInicial']);
			$dTF = new DataHora($_POST['dataFinal']);
			
			$con->executar("SELECT p.*, (SELECT SUM(pe.valor) as total FROM ".Sistema::$BDPrefixo."pedidos pe WHERE pe.sessao = p.id AND (pe.status = '".PedidoStatus::FECHADO."' OR pe.status = '".PedidoStatus::ENTREGA."') AND pe.data >= '".$dTI->mostrar("Ymd")."' AND pe.data <= '".$dTF->mostrar("Ymd")."') as valorcompras FROM ".Sistema::$BDPrefixo."pessoas p ORDER BY ".$campo." ".$order);
		
		}else
			$con->executar("SELECT p.*, (SELECT SUM(pe.valor) as total FROM ".Sistema::$BDPrefixo."pedidos pe WHERE pe.sessao = p.id AND (pe.status = '".PedidoStatus::FECHADO."' OR pe.status = '".PedidoStatus::ENTREGA."')) as valorcompras FROM ".Sistema::$BDPrefixo."pessoas p ORDER BY ".$campo." ".$order);
				
	}
	
	function repeatTemplateByArray($con){
		
		global $iGR;
		
		$lP = new ListaPessoas;
		
		while($rs = $con->getRegistro()){
			
			if(($rs['valorcompras'] > 0 && ($_POST['ordenar'] == 1 || $_POST['ordenar'] == 2)) || $_POST['ordernar'] == null){
			
				$lP->condicoes('', $rs['id'], ListaPessoas::ID);
				$p = $lP->listar();
				
				$iGR->repetir('repetir->Clientes');
				$iGR->enterRepeticao()->trocar('id.Cliente', $p->getId());
				$iGR->enterRepeticao()->trocar('nome.Cliente', $p->nome);
				$iGR->enterRepeticao()->trocar('dataNascimento.Cliente', $p->getDataNasc() ? $p->getDataNasc()->mostrar() : '');
				$iGR->enterRepeticao()->trocar('cpf.Cliente', $p->cpf ? $p->cpf : $p->cnpj);
				
				if($p->getTelefone()->getTotal() > 0){
					$tel = $p->getTelefone()->listar();
					$iGR->enterRepeticao()->trocar('telefone.Telefone.Cliente', $tel->ddd."-".$tel->telefone);
				}
				
				if($p->getEndereco()->getTotal() > 0){
					$end = $p->getEndereco()->listar();
					$iGR->enterRepeticao()->trocar('logradouro.Endereco.Cliente', $end->logradouro);
					$iGR->enterRepeticao()->trocar('numero.Endereco.Cliente', $end->numero);
					$iGR->enterRepeticao()->trocar('bairro.Endereco.Cliente', $end->bairro);
					$iGR->enterRepeticao()->trocar('cidade.Endereco.Cliente', $end->cidade);
					$iGR->enterRepeticao()->trocar('estado.Endereco.Cliente', $end->estado);
					$iGR->enterRepeticao()->trocar('cep.Endereco.Cliente', $end->getCep());
				}
				
				if($p->getEmail()->getTotal() > 0){
					$email = $p->getEmail()->listar();
					$iGR->enterRepeticao()->trocar('email.Email.Cliente', $email->email);
				}
				
				$iGR->enterRepeticao()->trocar('moeda', "R$");
				$iGR->enterRepeticao()->trocar('valorCompras.Cliente', Numero::__CreateNumero($rs['valorcompras'])->moeda());
			
			}
			
		}
		
	}
	
	$iGR->createRepeticao('repetir->Clientes');
	repeatTemplateByArray($con);
	
	$pronto = $iGR->concluir();
	echo $pronto;exit;	
	
}

$tituloPagina = 'Relatórios > Clientes';

$iRel = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/clientes.html"));

$dT = new DataHora;
$iRel->trocar('data', $dT->mostrar("d/m"));

$iRel->createJavaScript();
$javaScript .= $iRel->javaScript->concluir();

$includePagina = $iRel->concluir();

?>