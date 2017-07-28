<?php

importar('LojaVirtual.Produtos.Produto');
importar('LojaVirtual.Pedidos.Pedido');
importar('LojaVirtual.Pedidos.PedidoEnderecoEntrega');

class PedidoItem extends Produto {
	
	private	$enderecoEntrega;
	private	$valorFrete;
	private	$pedido;
	
	public 	$quantidade;
	public	$observacao;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->enderecoEntrega	= new PedidoEnderecoEntrega;
		$this->quantidade		= 1;
		$this->valorFrete		= new Numero;
		$this->observacao 		= '';
		$this->pedido			= new Pedido;
		
	}
	
	public function setPedido(Pedido $obj){
		$this->pedido = $obj;
	}
	
	public function getPedido(){
		return $this->pedido;
	}
	
	public function getSubTotal(){
		
		$sT = new Numero($this->valor->formatar()*$this->quantidade);
		return $sT;
		
	}
	
	public function setEnderecoEntrega(PedidoEnderecoEntrega $end){
		$this->enderecoEntrega = $end;	
	}
	
	public function getEnderecoEntrega(){
		return $this->enderecoEntrega;	
	}
	
	public function setValorFrete($valor){
		$this->valorFrete->num = (string) $valor;
	}
	
	public function getValorFrete(){
		return $this->valorFrete;
	}
	
	public static function __ProdutoToPedidoItem(Produto $p){
		
		$ped = new PedidoItem($p->getId());
		
		$ped->setProdutoPai(new Produto($p->getProdutoPai()), true);
		
		$ped->codigo 		= $p->codigo;
		$ped->nome 			= $p->nome;
		
		$ped->peso			= $p->peso;
		$ped->valorCusto	= $p->valorCusto;
		$ped->valorReal		= $p->valorReal;
		$ped->valorVenda	= $p->valorVenda;
		$ped->largura		= $p->largura;
		$ped->altura		= $p->altura;
		$ped->comprimento	= $p->comprimento;
		$ped->frete			= $p->frete;
		$ped->tipoPedido	= $p->tipoPedido;
		
		$ped->estoque		= $p->estoque;
		$ped->ordem			= $p->ordem;
		$ped->tipoUnidade	= $p->tipoUnidade;
		$ped->quantidadeu	= $p->quantidadeu;
		$ped->descricao		= $p->descricao;
		
		$ped->disponivel	= $p->disponivel;
		$ped->promocao		= $p->promocao;
		$ped->lancamento	= $p->lancamento;
		$ped->removido		= $p->removido;		
		
		$ped->setDataCadastro($p->getDataCadastro());
		$ped->setURL($p->getURL());
		$ped->setVideo($p->getVideo());
		$ped->setMarca($p->getMarca());
		
		return $ped;
		
	}
	
}

?>