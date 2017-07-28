<?php

importar("Geral.Lista");
importar("Geral.Idiomas.Idioma");

class ListaIdiomas extends Lista {
	
	const ID 		= 'id';
	const SIGLA 	= 'sigla';
	const NOME	 	= 'nome';
	const IMAGEM 	= 'imagem';
	
	public function __construct(){
		parent::__construct('idiomas');	
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Idioma($info[self::ID]);
			$temp->sigla 	= $info[self::SIGLA];
			$temp->nome		= $info[self::NOME];
			
			if(!empty($info[self::IMAGEM]))
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataIdiomas.$info[self::IMAGEM])));
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Idioma &$i){
		
		if($i->getImagem()->nome != ''){
			$i->getImagem()->open();
			$nomeImagem = $i->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataIdiomas);
		}
		try{
			
			$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::SIGLA.", ".self::NOME.", ".self::IMAGEM.") VALUES('".$i->sigla."','".$i->nome."','".$nomeImagem."')");
			$id = $this->con->getId();
			
			$l = new ListaIdiomas;
			$l->condicoes('', $id, self::ID);
			$i = $l->listar();
			
			$tr[count($tr)] = 'E-mail ou senha inválidos';
			$tr[count($tr)] = 'Resgate de senha';
			$tr[count($tr)] = 'E-mail com sua senha enviado com sucesso';
			$tr[count($tr)] = 'não cadastrado';
			$tr[count($tr)] = 'Contato enviado com sucesso';
			$tr[count($tr)] = 'Em breve entraremos em contato';
			$tr[count($tr)] = 'Dados Cadastrais alterados com sucesso';
			$tr[count($tr)] = 'já cadastrado';
			$tr[count($tr)] = 'Cadastro realizado com sucesso';
			$tr[count($tr)] = 'Desde já agradecemos por seu cadastro. Abaixo os dados de seu cadastro';
			$tr[count($tr)] = 'Nome';
			$tr[count($tr)] = 'Data de Nascimento';
			$tr[count($tr)] = 'R.G.';
			$tr[count($tr)] = 'Razão Social';
			$tr[count($tr)] = 'Nome Fantasia';
			$tr[count($tr)] = 'Estado';
			$tr[count($tr)] = 'Cidade';
			$tr[count($tr)] = 'Endereço';
			$tr[count($tr)] = 'Número';
			$tr[count($tr)] = 'Complemento';
			$tr[count($tr)] = 'Bairro';
			$tr[count($tr)] = 'CEP';
			$tr[count($tr)] = 'Telefone';
			$tr[count($tr)] = 'Celular';
			$tr[count($tr)] = 'Senha';
			$tr[count($tr)] = 'Atenciosamente';
			$tr[count($tr)] = 'busca realizada por';
			$tr[count($tr)] = 'nenhum item encontrado por';
			$tr[count($tr)] = 'Encomendar';
			$tr[count($tr)] = 'Sob Consulta';
			$tr[count($tr)] = 'Cor';
			$tr[count($tr)] = 'Tamanho';
			$tr[count($tr)] = 'Pedra';
			$tr[count($tr)] = 'Não há nenhum item';
			$tr[count($tr)] = 'Há';
			$tr[count($tr)] = 'item(s)';
			$tr[count($tr)] = 'Produtos';
			$tr[count($tr)] = 'Encomenda efetuada com sucesso';
			$tr[count($tr)] = 'Pedido de orçamento';
			$tr[count($tr)] = 'Usuário Anônimo';
			$tr[count($tr)] = 'Pedido de Orçamento enviado com sucesso';
			$tr[count($tr)] = 'indicou um produto para você';
			$tr[count($tr)] = 'E-mail de indicação enviado com sucesso';
			$tr[count($tr)] = 'Usuário Anônimo';
			$tr[count($tr)] = 'Aberto';
			$tr[count($tr)] = 'Cancelado';
			$tr[count($tr)] = 'Aguardando Pagamento';
			$tr[count($tr)] = 'Pagamento Concluído';
			$tr[count($tr)] = 'Produto Enviado';
			$tr[count($tr)] = 'Pagamento em Análise';
			$tr[count($tr)] = 'Aguardando Contato';
			$tr[count($tr)] = 'Pedido gerado com sucesso';
			$tr[count($tr)] = 'Status de Pedido alterado';
			
			foreach($tr as $v){
				if(empty($i->getTraducaoByConteudo($v, '', false)->traducao)){
					$t = new Traducao;
					$t->conteudo = $v;
					$t->traducao = $v;
					$i->addTraducao($t);
				}
			}
			
		}catch(Exception $e){
			
			throw new Exception($e->getMessage());
			
		}
		
	}
	
	public function alterar(Idioma $i){
		
		$where = "WHERE ".self::ID." = '".$i->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SIGLA, 	$i->sigla, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME,  	$i->nome, $where);
		
		if($i->getImagem()->nome != ''){
			$i->getImagem()->open();
			$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, $i->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataIdiomas), $where);
		}
	}
	
	public function deletar(Idioma $i){
		
		$where = "WHERE ".self::ID." = '".$i->getId()."'";
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataIdiomas.$i->getImagem()->nome.'.'.$i->getImagem()->extensao);
		
		$lT = new ListaTraducoes;
		$lT->condicoes('', $i->getId(), ListaTraducoes::IDIOMA);
		while($t = $lT->listar()){
			$lT->deletar($t);
			$lT->setParametros(0);
		}
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>