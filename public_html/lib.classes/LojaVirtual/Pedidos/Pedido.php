<?php

importar('Geral.Objeto');
importar('Utils.Dados.DataHora');
importar('Utils.Dados.Numero');
importar('Utils.EnvioEmail');
importar('Geral.InterFaces');
importar('LojaVirtual.Pedidos.PedidoPagamento');
importar('LojaVirtual.Pedidos.Pagamentos.PagamentoPagSeguro');
importar('LojaVirtual.Pedidos.Pagamentos.PagamentoDeposito');
importar('LojaVirtual.Pedidos.PedidoEnderecoEntrega');
importar('LojaVirtual.Pedidos.PedidoStatus');
importar('LojaVirtual.Pedidos.Lista.ListaPedidoItens');
importar('TemTudoAqui.Usuarios.Pessoa');

class Pedido extends Objeto {
	
	private		$data;
	private		$valor;
	private		$status;
	private		$endereco;
	private		$itens;
	private		$idSessao;
	private		$desconto;
	private		$existeFrete;
	private		$freteGratis;
	private		$tipoPagamento;
	private		$vendedor;

	public 		$observacoes;
	public 		$quantidade;
	public		$estoque;

	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->data 			= new DataHora;
		$this->valor 			= new Numero;
		$this->desconto			= new Numero;
		$this->observacoes 		= '';
		$this->status 			= new PedidoStatus;
		$this->idSessao			= new Pessoa;
		$this->quantidade		= 0;
		$this->endereco			= new PedidoEnderecoEntrega;
		$this->estoque			= 0;
		$this->freteGratis		= false;
		$this->existeFrete		= false;
		$this->vendedor			= 0;

		$this->itens 			= new ListaPedidoItens;
		$a[1] = array('campo' => ListaPedidoItens::IDSESSAO, 'valor' => $this->id);
		$this->itens->condicoes($a);

	}

	public function setCliente(Pessoa $id){
		$this->idSessao = $id;	
	}

	public function getCliente(){
		return $this->idSessao;	
	}

	public function calcular(){

		if($this->getId() != ''){

			$itens 		= new ListaPedidoItens;
			$a[1] = array('campo' => ListaPedidoItens::IDSESSAO, 'valor' => $this->id);
			$itens->condicoes($a);
			$quantidade = 0;
			$total = 0;

			while($i = $itens->listar()){
				$quantidade += $i->quantidade;
				$total 		+= $i->getSubTotal()->num;
			}
			
			$this->valor = Numero::__CreateNumero($total);
			$this->quantidade = $quantidade;
			
		}
		
		return $this;

	}
	
	public function getValor(){
		return $this->valor;
	}
	
	public function setValor($v){
		$this->valor = Numero::__CreateNumero($v);
		//if($v > 0)
			//$this->existeFrete = true;
	}

	public function setDesconto($v){
		$this->desconto->num = $v;
	}

	public function getDesconto(){
		return $this->desconto;
	}

	public function setData(DataHora $vData){
		$this->data = $vData;	
	}

	public function getData(){
		return $this->data;	
	}

	public function setStatus($vStatus){
		$this->status->setStatus($vStatus);	
	}

	public function getStatus(){
		return $this->status;	
	}

	public function setVendedor($id){
		$this->vendedor = (int)$id;	
	}

	public function getVendedor(){
		return $this->vendedor;	
	}

	public function addItem(PedidoItem $vPI){
		
		if($this->getId() != ''){

			$lPI = new ListaPedidoItens;
			$a[1] = array('campo' => ListaPedidoItens::ID, 'valor' => $vPI->getId());
			$a[2] = array('campo' => ListaPedidoItens::IDSESSAO, 'valor' => $this->getId());

			$lPI->condicoes($a);
			
			if($lPI->getTotal() == 0)
				$lPI->inserir($vPI, $this);
			else
				$lPI->alterar($vPI, $this);
				
			$this->calcular();
			$this->getEndereco()->setValor(0);

		}
	
	}

	public function removeItem(PedidoItem $vPI){

		if($this->getId() != ''){
			$this->itens->deletar($vPI, $this);
		}
		
	}
	
	public function getItem(){
		return $this->itens;
	}

	public function setEndereco(PedidoEnderecoEntrega $end){
		
		$this->endereco = $end;
		
		$con = BDConexao::__Abrir();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."frete");
		$rs = $con->getRegistro();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."pagamentos");
		$rsP = $con->getRegistro();
		
		if($this->tipoPagamento == PagamentoPagSeguro::GetTipo() && $rsP['fretepagseguro']){
			$this->existeFrete = true;
			$this->freteGratis = false;
		}		
		if($rs['fretegratis']){
			$this->existeFrete = true;
			$this->freteGratis = true;
		}
		if($rs['ativocorreio']){
			$this->existeFrete = true;
		}
		
	}

	public function getEndereco(){
		return $this->endereco;	
	}
	
	public function calcularFrete(){
		
		$con = BDConexao::__Abrir();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."frete");
		$rs = $con->getRegistro();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."pagamentos");
		$rsP = $con->getRegistro();
		
		try{
			
			if($this->tipoPagamento == PagamentoPagSeguro::GetTipo() && $rsP['fretepagseguro']){
				$this->existeFrete = true;
				$this->freteGratis = false;
			}
			if($rs['ativocorreio'] && $this->getEndereco()->getValor()->num <= 0){
				$this->existeFrete = true;
				$this->calcularFreteCorreios();
			}
		}catch(Exception $e){
			
			if(!$rs['fretegratis'])
				throw new Exception($e->getMessage());
			
		}
		
		if($rs['fretegratis']){
			$this->getEndereco()->setValor(0);
			$this->existeFrete = true;
			$this->freteGratis = true;
		}elseif($this->valor->formatar() >= $rs['apartirvalorfretegratis'] && $rs['apartirvalorfretegratis'] > 0){
			$this->getEndereco()->setValor(0);
			$this->existeFrete = true;
			$this->freteGratis = true;
		}
		
	}
	
	public function calcularFreteCorreios(){
		
		if($this->getId() != '' && $this->getEndereco()->getCep() != ''){
		
			$valor = 0;
			$prazo = 0;
			
			$itens 		= new ListaPedidoItens;
			$a[1] = array('campo' => ListaPedidoItens::IDSESSAO, 'valor' => $this->id);
			$itens->condicoes($a);
			$quantidade = 0;
			$total = 0;
			$freteGratis = false;
			$semFrete = false;
			
			try{
			
				while($pI = $itens->listar()){
								
					if($pI->frete == Produto::FRETE_GRATIS)
						$freteGratis = true;	
					elseif($pI->frete == Produto::SEM_FRETE)
						$semFrete = true;	
					else
						for($i = 1; $i <= $pI->quantidade; $i++){
							$this->getEndereco()->addItemCorreios($pI->peso->num, $pI->comprimento->num, $pI->largura->num, $pI->altura->num);
						}
									
				}
				
				$pacotes = $this->getEndereco()->getPacotesCorreios();
				if(count($pacotes) > 0){
					foreach($pacotes as $k){
						$e = PedidoEnderecoEntrega::__CalcularValorCorreios($this->getEndereco()->getCep(), $this->getEndereco()->tipo, $k['p'], $k['c'] < PedidoEnderecoEntrega::COMPRIMENTO_MIN_CORREIOS ? PedidoEnderecoEntrega::COMPRIMENTO_MIN_CORREIOS: $k['c'], $k['l'] < PedidoEnderecoEntrega::LARGURA_MIN_CORREIOS ? PedidoEnderecoEntrega::LARGURA_MIN_CORREIOS: $k['l'], $k['a'] < PedidoEnderecoEntrega::ALTURA_MIN_CORREIOS ? PedidoEnderecoEntrega::ALTURA_MIN_CORREIOS: $k['a']);
						$valor += $e['valor'];
						$prazo = $e['prazo'] > $prazo ? $e['prazo'] : $prazo;
					}
				}
				
				if($valor == 0 && !$freteGratis && $semFrete)
					$this->existeFrete = false;
				if($valor == 0 && $freteGratis)
					$this->freteGratis = true;
				
				$this->getEndereco()->setValor($valor);
				$this->getEndereco()->prazo = $prazo;				
			
			}catch(Exception $e){
				
				throw new Exception($e->getMessage());
				
				$this->existeFrete = false;
				
				$this->getEndereco()->setValor(0);
				$this->getEndereco()->prazo = 0;
				
			}
			
		}else{
			$this->getEndereco()->setValor(0);
			$this->getEndereco()->prazo = 0;
		}
		
	}

	public static function __ParcelasPagSeguro($valor){
		
		$a[1] 	= $valor;
		$a[2] 	= $valor*0.51875;
		$a[3] 	= $valor*0.35007;
		$a[4] 	= $valor*0.26575;
		$a[5] 	= $valor*0.21518;
		$a[6] 	= $valor*0.18148;
		$a[7] 	= $valor*0.15743;
		$a[8] 	= $valor*0.13941;
		$a[9] 	= $valor*0.12540;
		$a[10] 	= $valor*0.11420;
		$a[11] 	= $valor*0.10505;
		$a[12] 	= $valor*0.09743;
		$a[13] 	= $valor*0.09099;
		$a[14] 	= $valor*0.08548;
		$a[15] 	= $valor*0.08071;
		$a[16] 	= $valor*0.07654;
		$a[17] 	= $valor*0.07287;
		$a[18] 	= $valor*0.06961;
		
		return $a;
		
	}	
	
	public function hasFrete(){
		return $this->existeFrete;
	}
	
	public function freeFrete(){
		return $this->freteGratis;
	}
	
	public function setTipoPagamento($tipo){
		$this->tipoPagamento = $tipo;
	}
	
	public function getTipoPagamento(){
		return $this->tipoPagamento;
	}
	
	public function checkout(PedidoPagamento $obj){
		
		$this->tipoPagamento = (string) $obj;
			
		$obj->setReferencia($this->id);
		$obj->setEndereco($this->endereco);
		$obj->setCliente($this->idSessao);
		$obj->setDesconto($this->getDesconto()->num);
		
		$itens 		= new ListaPedidoItens;
		$a[1] = array('campo' => ListaPedidoItens::IDSESSAO, 'valor' => $this->id);
		$itens->condicoes($a);
		while($i = $itens->listar()){
			$obj->addItem($i);
		}
		
		try{
			return $obj->checkout();
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}
		
	}
	
	public function sendEmail($assunto, Idioma $idioma = null, $vendedor = false){
		
		if(empty($idioma)){
			$idioma = new Idioma;
			$idioma->sigla = 'br';
		}elseif(!$idioma->getId()){
			$idioma = new Idioma;
			$idioma->sigla = 'br';			
		}
		
		$temE = new InterFaces(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/email-padrao.html"));
		$temEE = new InterFaces(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaPedidos/email-pedido.html"));
		$temEE->setSession($idioma->sigla);
		$temEE->trocar('lang', $idioma->sigla);
		
		$p = $this->getCliente();
	
		$endP = $p->getEndereco()->listar();
		$telP = $p->getTelefone()->listar();
		
		$lP = new ListaProdutos;
		
		$temEE->createRepeticao("repetir->PedidoItens");
		
		$con = BDConexao::__Abrir();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."frete");
		$rs = $con->getRegistro();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."pagamentos");
		$rsP = $con->getRegistro();
		
		$total = 0;
		$end = $this->getEndereco();		
		
		$temEE->condicao("condicao->Alterar.Endereco.Pedido", isset($_GET['alterar-endereco']) || $end->getCep() == '' || $end->logradouro == '' || $end->numero == '' || $end->bairro == '' || $end->getCidade()->nome == '' || $end->getEstado()->uf == '');
		$temEE->condicao("condicao->tipo.Endereco.Pedido", !empty($end->tipo));
		$temEE->trocar($end->tipo.'.Endereco.Pedido', "checked=\"checked\"");
		$temEE->trocar('cep.Endereco.Pedido', $end->getCep());
		$temEE->trocar('logradouro.Endereco.Pedido', $end->logradouro);
		$temEE->trocar('ddd.Telefone.Pedido', $tel->ddd);
		$temEE->trocar('telefone.Telefone.Pedido', $tel->telefone);
		$temEE->trocar('numero.Endereco.Pedido', $end->numero);
		$temEE->trocar('complemento.Endereco.Pedido', $end->complemento);
		$temEE->trocar('bairro.Endereco.Pedido', $end->bairro);
		$temEE->trocar('cidade.Endereco.Pedido', $end->getCidade()->nome);
		$temEE->trocar('estado.Endereco.Pedido', $end->getEstado()->uf);
		
		$temEE->trocar("tipoPagamento.Pedido", $this->getTipoPagamento());
		$temEE->trocar("status.Pedido", $idioma->getTraducaoByConteudo($this->getStatus())->traducao);
		
		$recuperar = true;
		
		$itens 		= new ListaPedidoItens;
		$a[1] = array('campo' => ListaPedidoItens::IDSESSAO, 'valor' => $this->id);
		$itens->condicoes($a);
		
		while($pI = $itens->listar()){
			
			if($pI){
				
				$lP->condicoes('', $pI->getProdutoPai(), ListaProdutos::ID);
				if($lP->getTotal() > 0)
					$produtoPai = $lP->listar();
				
				$cat = $produtoPai ? $produtoPai->getCategorias()->listar() : $pI->getCategorias()->listar();
				
				$temEE->repetir();
				$temEE->enterRepeticao()->trocar("n.PedidoItem", $this->getItem()->getParametros());
				$temEE->enterRepeticao()->trocar("id.PedidoItem", $pI->getId());
				$temEE->enterRepeticao()->trocar("quantidade.PedidoItem", $pI->quantidade);
				$temEE->enterRepeticao()->trocar("nome.PedidoItem", $idioma->getTraducaoByConteudo($pI->nome)->traducao.($pI->observacao != '' ? ' '.$pI->observacao : ''));
				
				$temEE->enterRepeticao()->trocar("valor.PedidoItem", "$ ".$pI->valor->moeda());	
							
				$valorP = $pI->valor;
				
				$temEE->enterRepeticao()->trocar("valorPonto.PedidoItem", (string) Numero::__CreateNumero(($valorP->formatar()))->formatar());
	
				$total += ($pI->valor->num*$pI->quantidade);
					
				if($pI->getImagens()->getTotal() > 0)
					$temEE->enterRepeticao()->trocar("imagem.PedidoItem", $pI->getImagens()->listar("DESC", ListaImagens::DESTAQUE)->getImage()->showHTML(60, 1000));
	
				if($pI->quantidade > $pI->estoque)
					$recuperar = false;
			}
	
		}
		
		$temEE->condicao('condicao->Desconto', $this->getDesconto()->num > 0);
		$temEE->trocar('desconto', "$ ".$this->getDesconto()->moeda());
		$total -= $this->getDesconto()->num;		
		
		$temEE->condicao("condicao->RecuperarPedido", $this->getStatus()->getStatus() == PedidoStatus::CANCELADO && $recuperar);
		$temEE->condicao("condicao->EfetivarPagamento", $this->getStatus()->getStatus() == PedidoStatus::COBRANCA);
		$temEE->trocar("linkFinalizar.Pedido", Sistema::$caminhoURL.$idioma->sigla."/finalizar-pedido&pedido=".$this->getId()."&recuperar");
		
		$temEE->condicao("condicao->DepositoPagamento", $this->getTipoPagamento() == PagamentoDeposito::GetTipo());
		$temEE->trocar("textoDeposito", nl2br($rsP['textodeposito']));
				
		$temEE->condicao('condicao->ExisteFrete', $this->hasFrete() && $this->getItem()->getTotal() > 0);
		$temEE->condicao('condicao->ExistePrazo', $this->getEndereco()->prazo > 0 && $this->getItem()->getTotal() > 0);
		$temEE->condicao('condicao->ExisteFreteCorreios', $rs['ativocorreio']);		
		$temEE->trocar("valor.Endereco.Cliente", $this->freeFrete() ? $idioma->getTraducaoByConteudo('Grátis')->traducao : ($end->getValor()->num > 0 ? "$ ".$end->getValor()->moeda() : ''));
		$temEE->trocar("tipo.Endereco.Pedido", PedidoEnderecoEntrega::GetNameType($end->tipo));
		$temEE->trocar("prazo.Endereco.Pedido", $this->getEndereco()->prazo);
		$temEE->trocar("total", "$ ".Numero::__CreateNumero($total+$end->getValor()->num)->moeda());
		
		$temEE->trocar("observacoes", $this->observacoes);		
		
		$temE->trocar('texto', $temEE->concluir());
		$msg = $temE->concluir();
		
		EnvioEmail::$de = Sistema::$nomeEmpresa."<".Sistema::$emailEmpresa.">";
		EnvioEmail::$assunto = $idioma->getTraducaoByConteudo($assunto)->traducao."!";
		EnvioEmail::$html = true;
		EnvioEmail::$msg = $msg;
		
		if(!$vendedor){
			EnvioEmail::$de = Sistema::$nomeEmpresa."<".Sistema::$emailEmpresa.">";
			EnvioEmail::$para = $p->emailPrimario;
		}else{
			EnvioEmail::$de = $p->nome."<".$p->emailPrimario.">";
			EnvioEmail::$para = Sistema::$emailEmpresa;
		}
		
		EnvioEmail::enviar();
		
	}

}

?>