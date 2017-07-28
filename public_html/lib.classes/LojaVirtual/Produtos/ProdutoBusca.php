<?php

importar("LojaVirtual.Produtos.ProdutoMarca");
importar("LojaVirtual.Produtos.Lista.ListaProdutos");
importar("LojaVirtual.Categorias.Lista.ListaProdutoCategorias");

class ProdutoBusca {
	
	private static $condicao;
	
	public static function todosNiveisCategorias(ProdutoCategoria $cate){
		
		$lC = new ListaProdutoCategorias;
		if(!$cat = $lC->condicoes('', $cate->getId(), ListaProdutoCategorias::ID)->listar())
			$cat = new ProdutoCategoria;
		
		if($cat->disponivel){
		
			if(!empty(self::$condicao))
				$cat->getProdutos()->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_produtos_categorias rpc INNER JOIN ".Sistema::$BDPrefixo."produtos p ON p.id = rpc.produto WHERE rpc.categoria = '".$cat->getId()."' ".self::$condicao)->setGroup(ListaProdutos::ID);
			
			while($prod = $cat->getProdutos()->listar()){
				$adicionar = true;
				if(!empty($a)){
					foreach($a as $p){
						if($p->getId() == $prod->getId()){
							$adicionar = false;
							break;
						}
					}
				}
				if($adicionar)
					$a[count($a)] = $prod;
			}
			
			while($cats = $cat->getSubCategorias()->listar("ASC")){
				
				$b = self::todosNiveisCategorias($cats);
				if(!empty($a) && !empty($b)){
					
					foreach($b as $prod){
							
						$adicionar = true;
						foreach($a as $p){
							if($prod->getId() == $p->getId()){
								$adicionar = false;
								break;
							}
						}
						
						if($adicionar)
							$a[count($a)] = $prod;
						
					}
					
				}
				elseif(!empty($b)) $a = $b;
				
			}
		
		}
		
		if(empty($a)) $a = array();
		
		return $a;
		
	}
	
	public static function buscaTodasCategorias($a = ''){
		
		self::$condicao = $a;
		
		$lC = new ListaProdutoCategorias;
		$lC->condicoes(array(1 => array('campo' => ListaProdutoCategorias::CATEGORIAPAI, 'valor' => '')));
		
		while($cat = $lC->listar())
				if(empty($prods))
					$prods = self::todosNiveisCategorias($cat);
				else{
					
					$array = self::todosNiveisCategorias($cat);
					
					foreach($array as $prod){
						
						$adicionar = true;
						foreach($prods as $p){
							if($prod->getId() == $p->getId()){
								$adicionar = false;
								break;
							}
						}
						
						if($adicionar)
							$prods[count($prods)] = $prod;
						
					}
				}
				
		return $prods;
		
	}
	
	public static function buscaUmaCategoria(ProdutoCategoria $cat, $a){
		
		self::$condicao = $a;
		
		return self::todosNiveisCategorias($cat);
		
	}
	
	public static function buscaUmaMarca(ProdutoMarca $marca){
		
		$lP = new ListaProdutos;
		
		while($prod = $lP->listar())
			if($prod->getMarca()->getId() == $marca->getId())
				$a[count($a)] = $prod;
		
		if(empty($a)) $a = array();
		
		return $a;
		
	}
	
}

?>