<?php

importar("Geral.Idiomas.Lista.ListaIdiomas");

$tituloPagina = 'Configurações > Idiomas > Traduções';

$iTLT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/listarTraducoes.html"));

$iTLT->trocar("linkDeletar.Traducao", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLT->trocar("linkBuscar.Traducao", "?p=".$_GET['p']."&");

$lI			= new ListaIdiomas;
$lI->condicoes('', $_GET['idioma'], ListaIdiomas::ID);

if($lI->getTotal() > 0)
	$i = $lI->listar();
else
	$i = new Idioma;

if(!empty($_GET['deletar'])){
	
	$lT = new ListaTraducoes;
	$lT->condicoes('', $_GET['deletar'], ListaTraducoes::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Tradução removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$iTLT->createRepeticao("repetir->Traducoes");

if(!empty($_GET['busca']))
     $i->getTraducoes()->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTLT->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTLT->trocar("linkCadastrar.Traducao", "?p=".$_GET['p']."&a=cadastrarTraducao&idioma=".$i->getId());

$cond[1] = array('campo' => ListaTraducoes::IDCONTEUDO, 'valor' => '');

$i->getTraducoes()->condicoes($cond);

while($tr = $i->getTraducoes()->listar("ASC", ListaTraducoes::CONTEUDO)){
	  
	   $iTLT->repetir();
	   
	   $iTLT->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $i->getTraducoes()->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLT->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTLT->enterRepeticao()->trocar("id.Traducao", $tr->getId());
	   $iTLT->enterRepeticao()->trocar("conteudo.Traducao", strlen(strip_tags($tr->conteudo)) > 80 ? substr(strip_tags($tr->conteudo), 0, 77)."..." : strip_tags($tr->conteudo));
	   $iTLT->enterRepeticao()->trocar("traducao.Traducao", strlen(strip_tags($tr->traducao)) > 80 ? substr(strip_tags($tr->traducao), 0, 77)."..." : strip_tags($tr->traducao));
	   
	   $iTLT->enterRepeticao()->trocar("linkAlterar.Traducao", "?p=".$_GET['p']."&a=alterarTraducao&traducao=".$tr->getId());
	   
	   $iTLT->enterRepeticao()->condicao("condicaoVisualizar", $tr->tipo == 1);
	 
}

$iTLT->trocar("linkVoltar.Traducao", "?p=".$_GET['p']."&a=listarIdiomas");

$botoes = $iTLT->cutParte('botoes');

$includePagina = $iTLT->concluir();

?>