<?php

importar("Utils.Arquivos");
importar("Utils.Dados.Numero");

class CEP {
	
	public static function __InfoCEP($cep){  
		
		$num = new Numero($cep);
		$cep = $num->limparNumero()->num;
		
		$resultado = Arquivos::__AbrirArquivo('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string');  
    	if(!$resultado)
        	$resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";  
    	
    	parse_str($resultado, $retorno);   
    	return $retorno;  
	
	}
	
	public static function __CalculaFreteCorreios($servico, $origem, $destino, $peso) {
	  
		  /*if (!$sock = fsockopen('www.correios.com.br', 80, $errornro, $error, 60)) {
			throw new Exception($error, $errornro);
		  }
			
		  $msg = "GET /encomendas/precos/calculo.cfm?"
				."Servico={$servico}&cepOrigem={$origem}&cepDestino={$destino}"
				."&peso={$peso}&resposta=localhost HTTP/1.1\n"
				."Host: www.correios.com.br\nConnection: Close\n\n";
		
		  fwrite($sock, $msg);
		
		  while (!feof($sock)) {
			$line = fgets($sock);
			if (!preg_match('/^Location: \w+\?(.*)$/',$line, $match)) continue;
		  
			$data = array();
			foreach(split('&', $match[1]) as $item) {
			  $t = split('=', $item);
			  $data[$t[0]] = trim($t[1]);
			}
		
			break;
		  }
		  $data['Servico'] = urldecode($data['Servico']);
		  $data['erro'] = urldecode($data['erro']);
		
		  return $data;*/
		  
		  $cepOrigem = $origem;

          $peso = 2;

          $url = "http://www.correios.com.br/encomendas/precos/calculo.cfm?servico=".$servico."&cepOrigem=".$cepOrigem."&cepDestino=".          $destino."&peso=".$peso."&MaoPropria=N&avisoRecebimento=N&resposta=xml";

          $ini = @curl_init();
          if(!$ini){
            exit();
          }    
          curl_setopt($ini, CURLOPT_URL, $url);
          curl_setopt($ini, CURLOPT_HEADER, 0);
          ob_start();
          curl_exec($ini);
          curl_close($ini);
          $fonte = ob_get_contents();
          ob_end_clean();
	
		 $bsc = "/\<preco_postal>(.*)\<\/preco_postal>/";
		 
		 if(preg_match($bsc,$fonte,$retorno)){
			
			return number_format($retorno[1],2);
	
		 }
		  
	}
	
	public function calculaFreteTransportadora($destino, $peso){
		
		
		
	}

	
}

?>