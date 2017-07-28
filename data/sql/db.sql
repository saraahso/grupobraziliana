-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 28/07/2017 às 08:24
-- Versão do servidor: 5.6.35
-- Versão do PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Banco de dados: `grupobra_banco`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_arquivos`
--

CREATE TABLE `tta_arquivos` (
  `id` int(11) NOT NULL,
  `arquivo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `produtos` text COLLATE utf8_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_arquivos_categorias`
--

CREATE TABLE `tta_arquivos_categorias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_banners`
--

CREATE TABLE `tta_banners` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `flash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `datainicio` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `datafim` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(11) NOT NULL,
  `enderecourl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ativo` int(11) NOT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `largura` decimal(6,2) NOT NULL,
  `altura` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_banners_categorias`
--

CREATE TABLE `tta_banners_categorias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `largura` decimal(6,2) NOT NULL,
  `altura` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_cidade`
--

CREATE TABLE `tta_cidade` (
  `id` int(11) NOT NULL,
  `pais` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ddd` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_enderecos`
--

CREATE TABLE `tta_enderecos` (
  `id` int(11) NOT NULL,
  `ligacao` int(11) DEFAULT NULL,
  `logradouro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bairro` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pais` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_estado`
--

CREATE TABLE `tta_estado` (
  `id` int(11) NOT NULL,
  `pais` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `uf` varchar(2) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_eventos`
--

CREATE TABLE `tta_eventos` (
  `id` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `texto` int(11) NOT NULL,
  `data` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `local` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_frete`
--

CREATE TABLE `tta_frete` (
  `ceporigem` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `ativocorreio` tinyint(1) NOT NULL,
  `logincorreio` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `senhacorreio` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fretegratis` tinyint(1) NOT NULL,
  `apartirvalorfretegratis` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_galerias`
--

CREATE TABLE `tta_galerias` (
  `id` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `local` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `data` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_galerias_categorias`
--

CREATE TABLE `tta_galerias_categorias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` int(11) NOT NULL,
  `texto` int(11) NOT NULL,
  `largura` decimal(6,2) NOT NULL,
  `altura` decimal(6,2) NOT NULL,
  `larguram` decimal(6,2) NOT NULL,
  `alturam` decimal(6,2) NOT NULL,
  `largurap` decimal(6,2) NOT NULL,
  `alturap` decimal(6,2) NOT NULL,
  `protegido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_idiomas`
--

CREATE TABLE `tta_idiomas` (
  `id` int(11) NOT NULL,
  `sigla` varchar(10) CHARACTER SET latin1 NOT NULL,
  `nome` varchar(50) CHARACTER SET latin1 NOT NULL,
  `imagem` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_idiomas_traducoes`
--

CREATE TABLE `tta_idiomas_traducoes` (
  `id` int(11) NOT NULL,
  `idioma` int(11) NOT NULL,
  `conteudo` text COLLATE utf8_unicode_ci NOT NULL,
  `traducao` text COLLATE utf8_unicode_ci NOT NULL,
  `campoconteudo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tabelaconteudo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `idconteudo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_imagens`
--

CREATE TABLE `tta_imagens` (
  `id` int(11) NOT NULL,
  `sessao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idsessao` int(11) NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `legenda` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destaque` int(11) NOT NULL,
  `datacadastro` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_mailing`
--

CREATE TABLE `tta_mailing` (
  `id` int(11) NOT NULL,
  `pacote` int(11) DEFAULT NULL,
  `texto` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `data` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_mailing_pacotes`
--

CREATE TABLE `tta_mailing_pacotes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_mailing_pacotes_emails`
--

CREATE TABLE `tta_mailing_pacotes_emails` (
  `pacote` int(11) NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cidade` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datanasc` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_mailing_pacotes_envio`
--

CREATE TABLE `tta_mailing_pacotes_envio` (
  `mailing` int(11) NOT NULL DEFAULT '0',
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_marcadagua`
--

CREATE TABLE `tta_marcadagua` (
  `posicaohorizontal` int(11) NOT NULL,
  `posicaovertical` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `texto` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `produtos` tinyint(1) NOT NULL,
  `galerias` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_musicas`
--

CREATE TABLE `tta_musicas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `musica` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_musicas_categorias`
--

CREATE TABLE `tta_musicas_categorias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `subtitulo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravadora` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datalancamento` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `capa` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_noticias`
--

CREATE TABLE `tta_noticias` (
  `id` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `texto` int(11) NOT NULL,
  `data` varchar(16) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_noticias_categorias`
--

CREATE TABLE `tta_noticias_categorias` (
  `id` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `texto` int(11) NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_ofertascoletivas`
--

CREATE TABLE `tta_ofertascoletivas` (
  `id` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `empresa` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subtitulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destaques` text COLLATE utf8_unicode_ci NOT NULL,
  `regulamento` text COLLATE utf8_unicode_ci NOT NULL,
  `valororiginal` decimal(6,2) NOT NULL,
  `desconto` decimal(6,2) NOT NULL,
  `economia` decimal(6,2) NOT NULL,
  `valor` decimal(6,2) NOT NULL,
  `datainicio` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `datafim` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `comprasminima` int(11) NOT NULL,
  `comprasefetuadas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_ofertascoletivas_empresas`
--

CREATE TABLE `tta_ofertascoletivas_empresas` (
  `id` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `texto` int(11) NOT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `senha` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailsecundario` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rg` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpf` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datanasc` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razaosocial` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnpj` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL,
  `site` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datacadastro` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_ofertascoletivas_empresas_emails`
--

CREATE TABLE `tta_ofertascoletivas_empresas_emails` (
  `id` int(11) NOT NULL,
  `pessoa` int(11) NOT NULL,
  `descricao` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `principal` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_ofertascoletivas_empresas_enderecos`
--

CREATE TABLE `tta_ofertascoletivas_empresas_enderecos` (
  `id` int(11) NOT NULL,
  `ligacao` int(11) DEFAULT NULL,
  `logradouro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bairro` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pais` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_ofertascoletivas_empresas_telefones`
--

CREATE TABLE `tta_ofertascoletivas_empresas_telefones` (
  `id` int(11) NOT NULL,
  `ligacao` int(11) DEFAULT NULL,
  `local` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ddd` int(11) DEFAULT NULL,
  `telefone` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ramal` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_pagamentos`
--

CREATE TABLE `tta_pagamentos` (
  `tiposite` tinyint(1) NOT NULL,
  `tipopedido` tinyint(1) NOT NULL,
  `tipopedidoprodutostodosite` int(11) NOT NULL,
  `ativodesconto` tinyint(1) NOT NULL,
  `codigodesconto` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `porcentagemdesconto` decimal(11,2) NOT NULL,
  `ativopagseguro` tinyint(1) NOT NULL,
  `emailpagseguro` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tokenpagseguro` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `urlretornopagseguro` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fretepagseguro` tinyint(1) NOT NULL,
  `ativodeposito` tinyint(1) NOT NULL,
  `textodeposito` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_pais`
--

CREATE TABLE `tta_pais` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ddi` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_pedidos`
--

CREATE TABLE `tta_pedidos` (
  `id` int(11) NOT NULL,
  `sessao` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `observacoes` text COLLATE utf8_unicode_ci,
  `tipopagamento` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `valor` decimal(7,2) DEFAULT NULL,
  `desconto` decimal(20,2) NOT NULL,
  `data` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `estoque` int(11) NOT NULL,
  `vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_pedido_enderecos`
--

CREATE TABLE `tta_pedido_enderecos` (
  `id` int(11) NOT NULL,
  `ligacao` int(11) DEFAULT NULL,
  `logradouro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bairro` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pais` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `valor` decimal(4,2) DEFAULT NULL,
  `prazo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_pedido_itens`
--

CREATE TABLE `tta_pedido_itens` (
  `id` int(11) NOT NULL DEFAULT '0',
  `idpedido` int(11) NOT NULL,
  `marca` int(11) DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `peso` decimal(7,3) NOT NULL,
  `largura` decimal(7,2) NOT NULL,
  `altura` decimal(7,2) NOT NULL,
  `comprimento` decimal(7,2) NOT NULL,
  `valorcusto` decimal(7,2) NOT NULL,
  `valorreal` decimal(7,2) NOT NULL,
  `valorvenda` decimal(7,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `descricaopequena` text COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `disponivel` int(11) NOT NULL,
  `promocao` int(11) NOT NULL,
  `lancamento` int(11) NOT NULL,
  `destaque` int(11) NOT NULL,
  `removido` tinyint(1) NOT NULL,
  `cor` int(11) NOT NULL,
  `pedra` int(11) NOT NULL,
  `tamanho` int(11) NOT NULL,
  `ordem` int(11) NOT NULL,
  `tipounidade` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `quantidadeu` int(11) NOT NULL,
  `datacadastro` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `urlvideo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `frete` int(11) NOT NULL,
  `tipopedido` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `fretevalor` decimal(20,3) NOT NULL,
  `observacao` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_perguntas`
--

CREATE TABLE `tta_perguntas` (
  `id` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `texto` text COLLATE utf8_unicode_ci NOT NULL,
  `imagem` int(11) NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_perguntas_categorias`
--

CREATE TABLE `tta_perguntas_categorias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_pessoas`
--

CREATE TABLE `tta_pessoas` (
  `id` int(11) NOT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sobrenome` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `senha` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailsecundario` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rg` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpf` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datanasc` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razaosocial` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnpj` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL,
  `site` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `atacadista` int(11) NOT NULL,
  `datacadastro` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `origemcadastro` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_pessoas_emails`
--

CREATE TABLE `tta_pessoas_emails` (
  `id` int(11) NOT NULL,
  `pessoa` int(11) NOT NULL,
  `descricao` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `principal` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos`
--

CREATE TABLE `tta_produtos` (
  `id` int(11) NOT NULL,
  `produtopai` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `marca` int(11) DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `peso` decimal(7,3) NOT NULL,
  `largura` decimal(7,2) NOT NULL,
  `altura` decimal(7,2) NOT NULL,
  `comprimento` decimal(7,2) NOT NULL,
  `valorcusto` decimal(7,2) NOT NULL,
  `valorreal` decimal(7,2) NOT NULL,
  `valorvenda` decimal(7,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `descricaopequena` text COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `especificacao` text COLLATE utf8_unicode_ci NOT NULL,
  `manual` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `palavraschaves` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `disponivel` int(11) NOT NULL,
  `promocao` int(11) NOT NULL,
  `lancamento` int(11) NOT NULL,
  `destaque` int(11) NOT NULL,
  `removido` int(2) NOT NULL,
  `cor` int(11) NOT NULL,
  `pedra` int(11) NOT NULL,
  `tamanho` int(11) NOT NULL,
  `ordem` int(11) NOT NULL,
  `tipounidade` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `quantidade` int(11) NOT NULL,
  `datacadastro` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `urlvideo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `frete` int(11) NOT NULL,
  `tipopedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_categorias`
--

CREATE TABLE `tta_produtos_categorias` (
  `id` bigint(20) NOT NULL,
  `categoriapai` bigint(20) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL,
  `subreferencia` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nivel1` int(11) DEFAULT NULL,
  `nivel2` int(11) DEFAULT NULL,
  `nivel3` int(11) DEFAULT NULL,
  `disponivel` tinyint(1) NOT NULL,
  `visaounica` tinyint(1) NOT NULL,
  `home` tinyint(1) DEFAULT NULL,
  `cor` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descricaopequena` text COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_configuracoes`
--

CREATE TABLE `tta_produtos_configuracoes` (
  `produtosporpagina` int(11) NOT NULL,
  `listasubcategorias` int(11) NOT NULL,
  `produtosporsubcategoria` int(11) NOT NULL,
  `cambioreal` decimal(7,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_cores`
--

CREATE TABLE `tta_produtos_cores` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hexadecimal` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_encomenda`
--

CREATE TABLE `tta_produtos_encomenda` (
  `idproduto` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_marcas`
--

CREATE TABLE `tta_produtos_marcas` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enderecourl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `disponivel` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_opcoes`
--

CREATE TABLE `tta_produtos_opcoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  `multi` tinyint(1) NOT NULL,
  `filtro` tinyint(1) NOT NULL,
  `aberto` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_opcoes_gerados`
--

CREATE TABLE `tta_produtos_opcoes_gerados` (
  `id` int(11) NOT NULL,
  `produto` int(11) NOT NULL,
  `opcao` int(11) NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_opcoes_valores`
--

CREATE TABLE `tta_produtos_opcoes_valores` (
  `id` int(11) NOT NULL,
  `opcao` int(11) NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cor` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_pedras`
--

CREATE TABLE `tta_produtos_pedras` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_tamanhos`
--

CREATE TABLE `tta_produtos_tamanhos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_produtos_termos_procurados`
--

CREATE TABLE `tta_produtos_termos_procurados` (
  `termo` varchar(50) NOT NULL,
  `contador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_recados`
--

CREATE TABLE `tta_recados` (
  `id` int(11) NOT NULL,
  `sessao` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `idsessao` int(11) DEFAULT NULL,
  `texto` int(11) NOT NULL,
  `data` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `local` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `liberado` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_arquivos_categorias`
--

CREATE TABLE `tta_relacionamento_arquivos_categorias` (
  `arquivo` int(11) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_banners_categorias`
--

CREATE TABLE `tta_relacionamento_banners_categorias` (
  `banner` int(11) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_galerias_categorias`
--

CREATE TABLE `tta_relacionamento_galerias_categorias` (
  `galeria` int(11) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_musicas_categorias`
--

CREATE TABLE `tta_relacionamento_musicas_categorias` (
  `musica` int(11) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_noticias_categorias`
--

CREATE TABLE `tta_relacionamento_noticias_categorias` (
  `noticia` int(11) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_ofertascoletivas_categorias`
--

CREATE TABLE `tta_relacionamento_ofertascoletivas_categorias` (
  `ofertacoletiva` int(11) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_produtos_categorias`
--

CREATE TABLE `tta_relacionamento_produtos_categorias` (
  `produto` varchar(20) CHARACTER SET latin1 NOT NULL,
  `categoria` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_produtos_infos`
--

CREATE TABLE `tta_relacionamento_produtos_infos` (
  `produto` int(11) NOT NULL,
  `cor` int(11) NOT NULL,
  `tamanho` int(11) NOT NULL,
  `pedra` int(11) NOT NULL,
  `estoque` int(11) NOT NULL,
  `valor` decimal(7,2) NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_relacionamento_slides_categorias`
--

CREATE TABLE `tta_relacionamento_slides_categorias` (
  `slide` int(11) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_slides`
--

CREATE TABLE `tta_slides` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `legenda` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enderecourl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ativo` int(11) NOT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL,
  `segundos` int(11) NOT NULL,
  `corfundo` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `flash` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_slides_categorias`
--

CREATE TABLE `tta_slides_categorias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_telefones`
--

CREATE TABLE `tta_telefones` (
  `id` int(11) NOT NULL,
  `ligacao` int(11) DEFAULT NULL,
  `local` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ddd` int(11) DEFAULT NULL,
  `telefone` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ramal` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_textos`
--

CREATE TABLE `tta_textos` (
  `id` int(11) NOT NULL,
  `url` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subtitulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `textopequeno` text COLLATE utf8_unicode_ci NOT NULL,
  `texto` text COLLATE utf8_unicode_ci NOT NULL,
  `imagem` int(11) NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_tickets`
--

CREATE TABLE `tta_tickets` (
  `id` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `nivel` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `satisfacao` int(11) NOT NULL,
  `datahora_criacao` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `datahora_alteracao` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_tickets_postagens`
--

CREATE TABLE `tta_tickets_postagens` (
  `id` int(11) NOT NULL,
  `ticket` int(11) NOT NULL,
  `texto` text COLLATE utf8_unicode_ci NOT NULL,
  `arquivo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `datahora` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_urls`
--

CREATE TABLE `tta_urls` (
  `id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tabela` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `campo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_usuarios`
--

CREATE TABLE `tta_usuarios` (
  `id` int(11) NOT NULL,
  `nivel` int(11) NOT NULL,
  `nome` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `senha` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `texto` text COLLATE utf8_unicode_ci NOT NULL,
  `ensino` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tta_vendedores`
--

CREATE TABLE `tta_vendedores` (
  `id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `skype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `voip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ramal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL,
  `imagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `msn` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `tta_arquivos`
--
ALTER TABLE `tta_arquivos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_arquivos_categorias`
--
ALTER TABLE `tta_arquivos_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_banners`
--
ALTER TABLE `tta_banners`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_banners_categorias`
--
ALTER TABLE `tta_banners_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_cidade`
--
ALTER TABLE `tta_cidade`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_enderecos`
--
ALTER TABLE `tta_enderecos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_estado`
--
ALTER TABLE `tta_estado`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_eventos`
--
ALTER TABLE `tta_eventos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_galerias`
--
ALTER TABLE `tta_galerias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_galerias_categorias`
--
ALTER TABLE `tta_galerias_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_idiomas`
--
ALTER TABLE `tta_idiomas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_idiomas_traducoes`
--
ALTER TABLE `tta_idiomas_traducoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_imagens`
--
ALTER TABLE `tta_imagens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tta_imagens_idsessao_index` (`idsessao`,`id`);

--
-- Índices de tabela `tta_mailing`
--
ALTER TABLE `tta_mailing`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_mailing_pacotes`
--
ALTER TABLE `tta_mailing_pacotes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_mailing_pacotes_emails`
--
ALTER TABLE `tta_mailing_pacotes_emails`
  ADD PRIMARY KEY (`pacote`,`email`);

--
-- Índices de tabela `tta_mailing_pacotes_envio`
--
ALTER TABLE `tta_mailing_pacotes_envio`
  ADD PRIMARY KEY (`mailing`,`email`);

--
-- Índices de tabela `tta_musicas`
--
ALTER TABLE `tta_musicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_musicas_categorias`
--
ALTER TABLE `tta_musicas_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_noticias`
--
ALTER TABLE `tta_noticias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_noticias_categorias`
--
ALTER TABLE `tta_noticias_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_ofertascoletivas`
--
ALTER TABLE `tta_ofertascoletivas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_ofertascoletivas_empresas`
--
ALTER TABLE `tta_ofertascoletivas_empresas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_ofertascoletivas_empresas_emails`
--
ALTER TABLE `tta_ofertascoletivas_empresas_emails`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_ofertascoletivas_empresas_enderecos`
--
ALTER TABLE `tta_ofertascoletivas_empresas_enderecos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_ofertascoletivas_empresas_telefones`
--
ALTER TABLE `tta_ofertascoletivas_empresas_telefones`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_pais`
--
ALTER TABLE `tta_pais`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_pedidos`
--
ALTER TABLE `tta_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_pedido_enderecos`
--
ALTER TABLE `tta_pedido_enderecos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_perguntas`
--
ALTER TABLE `tta_perguntas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_perguntas_categorias`
--
ALTER TABLE `tta_perguntas_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_pessoas`
--
ALTER TABLE `tta_pessoas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_pessoas_emails`
--
ALTER TABLE `tta_pessoas_emails`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_produtos`
--
ALTER TABLE `tta_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marca` (`marca`),
  ADD KEY `nome` (`nome`);

--
-- Índices de tabela `tta_produtos_categorias`
--
ALTER TABLE `tta_produtos_categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subreferencia` (`subreferencia`);

--
-- Índices de tabela `tta_produtos_cores`
--
ALTER TABLE `tta_produtos_cores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_produtos_marcas`
--
ALTER TABLE `tta_produtos_marcas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nome` (`nome`);

--
-- Índices de tabela `tta_produtos_opcoes`
--
ALTER TABLE `tta_produtos_opcoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_produtos_opcoes_gerados`
--
ALTER TABLE `tta_produtos_opcoes_gerados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_produtos_opcoes_valores`
--
ALTER TABLE `tta_produtos_opcoes_valores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_produtos_pedras`
--
ALTER TABLE `tta_produtos_pedras`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_produtos_tamanhos`
--
ALTER TABLE `tta_produtos_tamanhos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_recados`
--
ALTER TABLE `tta_recados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_relacionamento_produtos_categorias`
--
ALTER TABLE `tta_relacionamento_produtos_categorias`
  ADD PRIMARY KEY (`produto`,`categoria`),
  ADD UNIQUE KEY `tta_relacionamento_produtos_categorias_index` (`produto`,`categoria`);

--
-- Índices de tabela `tta_slides`
--
ALTER TABLE `tta_slides`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_slides_categorias`
--
ALTER TABLE `tta_slides_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_telefones`
--
ALTER TABLE `tta_telefones`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_textos`
--
ALTER TABLE `tta_textos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_tickets`
--
ALTER TABLE `tta_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_tickets_postagens`
--
ALTER TABLE `tta_tickets_postagens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_urls`
--
ALTER TABLE `tta_urls`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_usuarios`
--
ALTER TABLE `tta_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tta_vendedores`
--
ALTER TABLE `tta_vendedores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `tta_arquivos`
--
ALTER TABLE `tta_arquivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_arquivos_categorias`
--
ALTER TABLE `tta_arquivos_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_banners`
--
ALTER TABLE `tta_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_banners_categorias`
--
ALTER TABLE `tta_banners_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_cidade`
--
ALTER TABLE `tta_cidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_enderecos`
--
ALTER TABLE `tta_enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_estado`
--
ALTER TABLE `tta_estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_eventos`
--
ALTER TABLE `tta_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_galerias`
--
ALTER TABLE `tta_galerias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_galerias_categorias`
--
ALTER TABLE `tta_galerias_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_idiomas`
--
ALTER TABLE `tta_idiomas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_idiomas_traducoes`
--
ALTER TABLE `tta_idiomas_traducoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_imagens`
--
ALTER TABLE `tta_imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de tabela `tta_mailing`
--
ALTER TABLE `tta_mailing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_mailing_pacotes`
--
ALTER TABLE `tta_mailing_pacotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_musicas`
--
ALTER TABLE `tta_musicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_musicas_categorias`
--
ALTER TABLE `tta_musicas_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_noticias`
--
ALTER TABLE `tta_noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_noticias_categorias`
--
ALTER TABLE `tta_noticias_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_ofertascoletivas`
--
ALTER TABLE `tta_ofertascoletivas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_ofertascoletivas_empresas`
--
ALTER TABLE `tta_ofertascoletivas_empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_ofertascoletivas_empresas_emails`
--
ALTER TABLE `tta_ofertascoletivas_empresas_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_ofertascoletivas_empresas_enderecos`
--
ALTER TABLE `tta_ofertascoletivas_empresas_enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_ofertascoletivas_empresas_telefones`
--
ALTER TABLE `tta_ofertascoletivas_empresas_telefones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_pais`
--
ALTER TABLE `tta_pais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_pedidos`
--
ALTER TABLE `tta_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_pedido_enderecos`
--
ALTER TABLE `tta_pedido_enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_perguntas`
--
ALTER TABLE `tta_perguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_perguntas_categorias`
--
ALTER TABLE `tta_perguntas_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_pessoas`
--
ALTER TABLE `tta_pessoas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_pessoas_emails`
--
ALTER TABLE `tta_pessoas_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_produtos`
--
ALTER TABLE `tta_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `tta_produtos_categorias`
--
ALTER TABLE `tta_produtos_categorias`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT de tabela `tta_produtos_cores`
--
ALTER TABLE `tta_produtos_cores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_produtos_marcas`
--
ALTER TABLE `tta_produtos_marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de tabela `tta_produtos_opcoes`
--
ALTER TABLE `tta_produtos_opcoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_produtos_opcoes_gerados`
--
ALTER TABLE `tta_produtos_opcoes_gerados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_produtos_opcoes_valores`
--
ALTER TABLE `tta_produtos_opcoes_valores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_produtos_pedras`
--
ALTER TABLE `tta_produtos_pedras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_produtos_tamanhos`
--
ALTER TABLE `tta_produtos_tamanhos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_recados`
--
ALTER TABLE `tta_recados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_slides`
--
ALTER TABLE `tta_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_slides_categorias`
--
ALTER TABLE `tta_slides_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_telefones`
--
ALTER TABLE `tta_telefones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_textos`
--
ALTER TABLE `tta_textos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_tickets`
--
ALTER TABLE `tta_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_tickets_postagens`
--
ALTER TABLE `tta_tickets_postagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tta_urls`
--
ALTER TABLE `tta_urls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT de tabela `tta_usuarios`
--
ALTER TABLE `tta_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `tta_vendedores`
--
ALTER TABLE `tta_vendedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;