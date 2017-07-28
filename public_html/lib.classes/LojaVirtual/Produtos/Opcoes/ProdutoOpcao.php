<?php

importar("Geral.Objeto");
importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcaoValores");

class ProdutoOpcao extends Objeto {
		
	const		TEXTO	= 0;
	const		IMAGEM	= 1;
	const		COR		= 2;
		
	protected	$valores;
		
	public		$nome;
	public		$tipo;
	public		$multi 	= false;
	public		$filtro = false;
	public		$aberto = false;
	
	public function __construct($id = ''){
		
		parent::__construct($id);

		$this->nome 	= '';
		$this->tipo		= 0;
		
		$this->valores	= new ListaProdutoOpcaoValores;
		$aR[1] = array('campo' => ListaProdutoOpcaoValores::OPCAO, 'valor' => $this->getId());
		$this->valores->condicoes($aR);
		
	}
	
	public function addValor(ProdutoOpcaoValor $obj){
		
		if($this->getId() != ''){
			
			$this->valores->inserir($obj, $this);
			
		}
		
	}
	
	public function getValores(){
		return $this->valores;
	}
	
	public static function __GetTipo($tipo){
		
		if($tipo == 0)
			return 'Texto';
		elseif($tipo == 1)
			return 'Imagem';
		elseif($tipo == 2)
			return 'Cor';
		
	}
	
}

?>