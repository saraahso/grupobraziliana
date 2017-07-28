<?php

importar("Utilidades.Noticias.Lista.ListaNoticias");

$tituloPagina = 'Utilidades > Noticias';

$iLN = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarNoticias.html"));

$iLN->trocar("linkDeletar.Noticia", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLN->trocar("linkBuscar.Noticia", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lN = new ListaNoticias;
	$lN->condicoes('', $_GET['deletar'], ListaNoticias::ID);
	
	if($lN->getTotal() > 0){
		
		try{
			$lN->deletar($lN->listar());
			$javaScript .= Aviso::criar("Noticia removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lN = new ListaNoticias;
$lN->condicoes();
$iLN->createRepeticao("repetir->Noticias");

if(!empty($_GET['busca']))
     $lN->condicoes('', '', '', '', "SELECT n.* FROM ".Sistema::$BDPrefixo."noticias n, ".Sistema::$BDPrefixo."textos t WHERE t.titulo LIKE '%".$_GET['busca']."%' AND t.url = '' AND n.texto = t.id");

$iLN->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iLN->trocar("linkCadastrar.Noticia", "?p=".$_GET['p']."&a=cadastrarNoticia");
$iLN->trocar('total.ListaNoticias', $lN->getTotal());

$num = 20;
$primeiro = $_GET['pag']*$num;
$total = $lN->getTotal();
$max = ceil($total/$num);
$lN->setParametros($primeiro);

while($not = $lN->setParametros($num+$primeiro, 'limite')->listar("DESC", ListaNoticias::DATA)){
	  
	   $iLN->repetir();
	   
	   $iLN->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lN->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLN->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLN->enterRepeticao()->trocar("id.Noticia", $not->getId());
	   $iLN->enterRepeticao()->trocar("titulo.Noticia", $not->getTexto()->titulo);

	   $iLN->enterRepeticao()->trocar("linkAlterar.Noticia", "?p=".$_GET['p']."&a=alterarNoticia&noticia=".$not->getId());
	   
	   $iLN->enterRepeticao()->condicao("condicaoVisualizar", $not->tipo == 1);
	 
}

$iLN->createRepeticao("repetir->Paginacao");
for($i = 0; $i < $max; $i++){

	$iLN->repetir();
	$iLN->enterRepeticao()->trocar("numero.Paginacao", $i+1);
	$iLN->enterRepeticao()->trocar("linkVisualizar.Paginacao", Sistema::$adminCaminhoURL."?p=SistemaUtilidades&a=listarNoticias&pag=".$i);
	$iLN->enterRepeticao()->condicao("condicao->atual.Paginacao", !($i == $_GET['pag']));
	
}

$iLN->trocar("linkVoltar", "?p=".$_GET['p']."&a=utilidades");

$botoes = $iLN->cutParte('botoes');

$includePagina = $iLN->concluir();

?>