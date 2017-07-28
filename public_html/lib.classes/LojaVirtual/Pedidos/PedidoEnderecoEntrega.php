<?php

importar('TemTudoAqui.Usuarios.Endereco');
importar('LojaVirtual.Pedidos.PedidoItem');
importar("Utils.WebService.NuSoap.nusoap");

class PedidoEnderecoEntrega extends Endereco {
	
	const	FRETE_TIPO_PAC				= 'pac';
	const	FRETE_TIPO_SEDEX			= 'sedex';
	
	const	FRETE_PAC_CONTRATO 			= 41068;
	const	FRETE_PAC 					= 41106;
	const	FRETE_SEDEX_CONTRATO 		= 40096;
	const	FRETE_SEDEX			 		= 40010;
	
	const	COMPRIMENTO_MIN_CORREIOS	= 16;
	const	COMPRIMENTO_MAX_CORREIOS	= 105;
	const	LARGURA_MIN_CORREIOS	 	= 11;
	const	LARGURA_MAX_CORREIOS		= 105;
	const	ALTURA_MIN_CORREIOS	 		= 2;
	const	ALTURA_MAX_CORREIOS			= 105;
	
	private $valor;
	private	$pacotesCorreios;
	
	public	$tipo;	
	public	$prazo;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->valor = new Numero;
		$this->tipo = 0;
		$this->prazo = 0;
		
	}
	
	public function getValor(){
		return $this->valor;
	}
	
	public function setValor($num){
		return $this->valor = new Numero($num);
	}
	
	public function addItemCorreios($peso, $c, $l, $a){
		
		if(empty($this->pacotesCorreios))
			$this->pacotesCorreios[0] = array('p'=> 0, 'c'=> 0, 'l'=> 0, 'a'=> 0);
		
		if($peso > 30.00 || $c > 105.00 || $l > 105.00 || $a > 105.00)
			throw new Exception("Item com dimensões ou peso inválidos para o Correios");
			
		$novo = true;
		
		for($k = 0; $k < count($this->pacotesCorreios); $k++){
			
			if(($this->pacotesCorreios[$k]['p']+$peso) <= 30.00){
				
				if($c < $l && ($this->pacotesCorreios[$k]['c']+$c) <= 150.00){
					
					if($c < $a){
						$this->pacotesCorreios[$k]['c'] += $c;
						$this->pacotesCorreios[$k]['l'] = $this->pacotesCorreios[$k]['l'] > $l ? $this->pacotesCorreios[$k]['l'] : $l;
						$this->pacotesCorreios[$k]['a'] = $this->pacotesCorreios[$k]['a'] > $a ? $this->pacotesCorreios[$k]['a'] : $a;
						$this->pacotesCorreios[$k]['p'] += $peso;
						$novo = false;
						break;
					}elseif(($this->pacotesCorreios[$k]['a']+$a) <= 105.00){
						$this->pacotesCorreios[$k]['a'] += $a;
						$this->pacotesCorreios[$k]['l'] = $this->pacotesCorreios[$k]['l'] > $l ? $this->pacotesCorreios[$k]['l'] : $l;
						$this->pacotesCorreios[$k]['c'] = $this->pacotesCorreios[$k]['c'] > $c ? $this->pacotesCorreios[$k]['c'] : $c;
						$this->pacotesCorreios[$k]['p'] += $peso;
						$novo = false;
						break;
					}
					
				}elseif(($this->pacotesCorreios[$k]['l']+$l) <= 105.00){
					
					if($l < $a){
						$this->pacotesCorreios[$k]['l'] += $l;
						$this->pacotesCorreios[$k]['c'] = $this->pacotesCorreios[$k]['c'] > $c ? $this->pacotesCorreios[$k]['c'] : $c;
						$this->pacotesCorreios[$k]['a'] = $this->pacotesCorreios[$k]['a'] > $a ? $this->pacotesCorreios[$k]['a'] : $a;
						$this->pacotesCorreios[$k]['p'] += $peso;
						$novo = false;
						break;
					}elseif(($this->pacotesCorreios[$k]['a']+$a) <= 105.00){
						$this->pacotesCorreios[$k]['a'] += $a;
						$this->pacotesCorreios[$k]['l'] = $this->pacotesCorreios[$k]['l'] > $l ? $this->pacotesCorreios[$k]['l'] : $l;
						$this->pacotesCorreios[$k]['c'] = $this->pacotesCorreios[$k]['c'] > $c ? $this->pacotesCorreios[$k]['c'] : $c;
						$this->pacotesCorreios[$k]['p'] += $peso;
						$novo = false;
						break;
					}
					
				}elseif(($this->pacotesCorreios[$k]['a']+$a) <= 105.00){
					
					$this->pacotesCorreios[$k]['a'] += $a;
					$this->pacotesCorreios[$k]['l'] = $this->pacotesCorreios[$k]['l'] > $l ? $this->pacotesCorreios[$k]['l'] : $l;
					$this->pacotesCorreios[$k]['c'] = $this->pacotesCorreios[$k]['c'] > $c ? $this->pacotesCorreios[$k]['c'] : $c;
					$this->pacotesCorreios[$k]['p'] += $peso;
					$novo = false;
					break;
					
				}
			
			}
		
		
		}
		
		if($novo){
			$this->pacotesCorreios[count($this->pacotesCorreios)] = array('p'=> $peso, 'c'=> $c, 'l'=> $l, 'a'=> $a);
		}
		
	}
	
	public function getPacotesCorreios(){
		return ($this->pacotesCorreios);
	}
	
	public function zerarPacotesCorreios(){
		unset($this->pacotesCorreios);
	}
	
	public static function __CalcularValorCorreios($cepDestino, $tipo, $p, $c, $l, $a){
		
		$con = BDConexao::__Abrir();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."frete");
		$rs = $con->getRegistro();
		
		$client = new nusoap_client("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL", true);
		
		if($rs['logincorreio'] && $rs['senhacorreio']){
		
			$result = $client->call('CalcPrecoPrazo', array('nCdEmpresa' => $rs['logincorreio'], 'sDsSenha' => $rs['senhacorreio'], 'nCdServico' => ($tipo == self::FRETE_TIPO_PAC ? self::FRETE_PAC_CONTRATO : self::FRETE_SEDEX_CONTRATO), 'sCepOrigem' => $rs['ceporigem'], 'sCepDestino' => $cepDestino, 'nVlPeso' => $p, 'nCdFormato' => 1, 'nVlComprimento' => $c, 'nVlAltura' => $a, 'nVlLargura' => $l, 'nVlDiametro' => $d ? $d : 0, 'sCdMaoPropria' => 'N', 'nVlValorDeclarado' => 0, 'sCdAvisoRecebimento' => 'N'));
		
			if(!empty($result["CalcPrecoPrazoResult"]['Servicos']['cServico']["MsgErro"]))
				$result = $client->call('CalcPrecoPrazo', array('nCdEmpresa' => '', 'sDsSenha' => '', 'nCdServico' => ($tipo == self::FRETE_TIPO_PAC ? self::FRETE_PAC : self::FRETE_SEDEX), 'sCepOrigem' => $rs['ceporigem'], 'sCepDestino' => $cepDestino, 'nVlPeso' => $p, 'nCdFormato' => 1, 'nVlComprimento' => $c, 'nVlAltura' => $a, 'nVlLargura' => $l, 'nVlDiametro' => $d ? $d : 0, 'sCdMaoPropria' => 'N', 'nVlValorDeclarado' => 0, 'sCdAvisoRecebimento' => 'N'));
			
		}else
			$result = $client->call('CalcPrecoPrazo', array('nCdEmpresa' => '', 'sDsSenha' => '', 'nCdServico' => ($tipo == self::FRETE_TIPO_PAC ? self::FRETE_PAC : self::FRETE_SEDEX), 'sCepOrigem' => $rs['ceporigem'], 'sCepDestino' => $cepDestino, 'nVlPeso' => $p, 'nCdFormato' => 1, 'nVlComprimento' => $c, 'nVlAltura' => $a, 'nVlLargura' => $l, 'nVlDiametro' => $d ? $d : 0, 'sCdMaoPropria' => 'N', 'nVlValorDeclarado' => 0, 'sCdAvisoRecebimento' => 'N'));
		
		if(!empty($result["CalcPrecoPrazoResult"]['Servicos']['cServico']["MsgErro"]))
			throw new Exception($result["CalcPrecoPrazoResult"]['Servicos']['cServico']["Erro"]." - ".$result["CalcPrecoPrazoResult"]['Servicos']['cServico']["MsgErro"]);
		
		$ar['valor'] = $result["CalcPrecoPrazoResult"]['Servicos']['cServico']['Valor'];
		$ar['prazo'] = $result["CalcPrecoPrazoResult"]['Servicos']['cServico']['PrazoEntrega'];
		
		return $ar;
		
	}
	
	public static function __EnderecoToPedidoEnderecoEntrega(Endereco $end){
		
		$pE = new PedidoEnderecoEntrega($end->getId());
		
		$pE->logradouro		= $end->logradouro;
		$pE->numero			= $end->numero;
		$pE->complemento	= $end->complemento;
		$pE->bairro			= $end->bairro;
		$pE->setCidade($end->getCidade());
		$pE->setEstado($end->getEstado());
		$pE->setPais($end->getPais());
		
		$pE->setCep($end->getCep());
		
		return $pE;
		
	}
	
	public static function GetNameType($type){
		
		if($type == self::FRETE_TIPO_PAC)
			return 'PAC';
		elseif($type == self::FRETE_TIPO_SEDEX)
			return 'Sedex';
		
	}
	
}

?>