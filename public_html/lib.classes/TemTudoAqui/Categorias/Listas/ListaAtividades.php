<?php

importar("Geral.Lista");
importar("Utils.Dados.Strings");

class ListaAtividades extends Lista {
	
	public function __construct(){
		
		parent::__construct('atividade');
		
	}
	
	public function listar($ordem = "ASC", $campo = 'id'){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){
		
			$at = new Atividade($info['id']);
			$at->titulo = $info['titulo'];
			
			return $at;
		
		}
		
	}
	
	public function inserir(Atividade &$at){
		
		$id = $this->getId("AT");	
		$this->con->executar("INSERT INTO ".BDPrefixo.$this->tabela."(id, titulo) VALUES('".$id."','".$at->titulo."')");
		
		$temp = new Atividade($id);
		$temp->titulo = $at->titulo;
		
		$at = $temp;
		
	}
	
	public function alterar(Atividade $at){
		
		$where = "WHERE id = '".$at->getId()."'";
		
		$this->con->alterar(BDPrefixo.$this->tabela, 'titulo', $at->titulo, $where);
		
	}
	
	public function deletar(Atividade $at){
		
		$where = "WHERE id = '".$at->getId()."'";
		
		while($sa = $at->getSubAtividade->listar())
				$at->getSubAtividade()->deletar($sa);
		
		$this->con->deletar(BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>