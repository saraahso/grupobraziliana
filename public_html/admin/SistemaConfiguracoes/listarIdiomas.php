<?php

importar("Geral.Idiomas.Lista.ListaIdiomas");

$tituloPagina = 'Configurações > Idiomas';

$iLI = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/listarIdiomas.html"));

$lI = new ListaIdiomas;

if(!empty($_GET['deletar'])){
	
	$lI = new ListaIdiomas;
	$i = $lI->condicoes('', $_GET['deletar'], ListaIdiomas::ID)->listar();
	
	if($lI->getTotal() > 0){
	
		$lI->deletar($i);
		
		$javaScript .= Aviso::criar('Idioma deletado com sucesso!');
	
	}else
		$javaScript .= Aviso::criar('Erro ao remover idioma!');	
		
	$lI->resetCondicoes();
	$lI->setParametros(0);	
	
}

$iLI->trocar("linkCadastrarIdioma", "?p=".$_GET['p']."&a=cadastrarIdioma");
$iLI->trocar("linkDeletar.Idioma", "?p=".$_GET['p']."&a=".$_GET['a']."&");

$iLI->trocar("linkVoltar.Idioma", "?p=".$_GET['p']."&a=configuracoes");

$iLI->createRepeticao('repetirIdiomas');
while($i = $lI->listar()){
	
	$iLI->repetir();
	$iLI->enterRepeticao()->trocar('nomeIdioma', $i->nome);
	$iLI->enterRepeticao()->trocar('idIdioma', $i->getId());
	
	$iLI->enterRepeticao()->trocar('linkAlterarIdioma', "?p=".$_GET['p']."&a=alterarIdioma&id=".$i->getId());
	$iLI->enterRepeticao()->trocar('linkVisualizar.Traducoes.Idioma', "?p=".$_GET['p']."&a=listarTraducoes&idioma=".$i->getId());
	
}

$iLI->createJavaScript();
$javaScript .= $iLI->javaScript->concluir();

$botoes = $iLI->cutParte('botoes');

$includePagina = $iLI->concluir();

?>