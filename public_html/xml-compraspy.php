<?php

include('lib.conf/includes.php');

importar("LojaVirtual.Produtos.Lista.ListaProdutos");

header("Content-Type: text/xml; charset=UTF-8",true);

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><rss version="2.0"></rss>');

$channel = $xml->addChild("channel");

$channel->addChild("title", 'Produtos da Marktronic Comunicação');
$channel->addChild("link", '');
$channel->addChild("description", ('Feed contendo os atributos obrigatórios e recomendados para cada produto da loja Marktronic Comunicação'));

$con = BDConexao::__Abrir();
$con2 = BDConexao::__Abrir();
  
$con->executar("SET NAMES 'UTF8';");

$sql = "SELECT p.*, uc.url as urlcategoria, rpc.categoria as categoria,
                (SELECT u.url FROM ".Sistema::$BDPrefixo."urls u WHERE u.id = p.url) as url,
                (SELECT i.imagem FROM ".Sistema::$BDPrefixo."imagens i WHERE i.sessao = 'produtos' AND i.idsessao = p.id ORDER BY i.destaque DESC LIMIT 1) as imagem
                    FROM ".Sistema::$BDPrefixo."produtos p
                    INNER JOIN ".Sistema::$BDPrefixo."relacionamento_produtos_categorias rpc
                        ON p.id = rpc.produto
                    INNER JOIN ".Sistema::$BDPrefixo."urls uc
                        ON uc.valor = rpc.categoria
                            AND uc.tabela = 'produtos_categorias'
                    WHERE p.disponivel = 1
                    ORDER BY p.nome ASC";

$con->executar($sql);
while($rs = $con->getRegistro()){

  if(!preg_match("!ZIONEER!", $rs['nome']) && !preg_match("!ZZ!", $rs['nome']) && $rs['valorreal'] > 0){
  
    if(!empty($rs['urlcategoria'])){

      $item = $channel->addChild("item");
      
      $item->addChild("title", $rs['nome']);
      $item->addChild("link", Sistema::$caminhoURL.'c/'.$rs['urlcategoria'].'-'.$rs['urlcategoria']."/p/".$rs['id'].'-'.$rs['url']);
      $item->addChild("description", str_replace(array('&', '<', '>', '\'', '"', PHP_EOL, '&#xD;'), array('&amp;', '<', '&gt;', '&apos;', '&quot;', '', ''), strip_tags((nl2br(html_entity_decode($rs['descricao']))))));
      $item->addChild("codigo", $rs['codigo']);
      $item->addChild("preco", Numero::__CreateNumero($rs['valorreal'])->formatar()." USD");

      if(!empty($rs['imagem'])){
        $img = Sistema::$caminhoURL.Sistema::$caminhoDataProdutos.$rs['imagem'];
        $item->addChild("link_imagem", $img);
      }else
        $item->addChild("link_imagem", "");

      $item->addChild("disponibilidade", "em estoque");

    }
    
  }
  
}

echo @$xml->asXML();