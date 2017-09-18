<?php

namespace TemTudoAqui;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * Produto
 *
 * @ORM\Table(name="tta_produtos")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\ProdutoRepository")
 */
class Produto extends AbstractEntity 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Produto")
     * @ORM\JoinColumn(name="produtopai", referencedColumnName="id")
     */
    private $produtopai;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="URL")
     * @ORM\JoinColumn(name="url", referencedColumnName="id")
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Marca", inversedBy="produtos")
     * @ORM\JoinColumn(name="marca", referencedColumnName="id")
     */
    private $marca;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", length=100, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", length=50, nullable=false)
     */
    private $codigo;

    /**
     * @var float
     *
     * @ORM\Column(name="peso", type="float", scale=7, precision=2, nullable=false)
     */
    private $peso;

    /**
     * @var float
     *
     * @ORM\Column(name="largura", type="float", scale=7, precision=2, nullable=false)
     */
    private $largura;

    /**
     * @var float
     *
     * @ORM\Column(name="altura", type="float", scale=7, precision=2, nullable=false)
     */
    private $altura;

    /**
     * @var float
     *
     * @ORM\Column(name="comprimento", type="float", scale=7, precision=2, nullable=false)
     */
    private $comprimento;

    /**
     * @var float
     *
     * @ORM\Column(name="valorcusto", type="float", scale=7, precision=2, nullable=false)
     */
    private $valorcusto;

    /**
     * @var float
     *
     * @ORM\Column(name="valorreal", type="float", scale=7, precision=2, nullable=false)
     */
    private $valorreal;

    /**
     * @var float
     *
     * @ORM\Column(name="valorVenda", type="float", scale=7, precision=2, nullable=false)
     */
    private $valorvenda;

    /**
     * @var integer
     *
     * @ORM\Column(name="estoque", type="integer", nullable=false)
     */
    private $estoque;

    /**
     * @var string
     *
     * @ORM\Column(name="descricaopequena", nullable=false)
     */
    private $descricaopequena;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", nullable=false)
     */
    private $descricao;

    /**
     * @var string
     *
     * @ORM\Column(name="especificacao", nullable=false)
     */
    private $especificacao;

    /**
     * @var string
     *
     * @ORM\Column(name="manual", length=255, nullable=false)
     */
    private $manual;

    /**
     * @var string
     *
     * @ORM\Column(name="palavraschaves", length=255, nullable=false)
     */
    private $palavraschaves;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disponivel", type="boolean", nullable=false)
     */
    private $disponivel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="promocao", type="boolean", nullable=false)
     */
    private $promocao;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lancamento", type="boolean", nullable=false)
     */
    private $lancamento;

    /**
     * @var boolean
     *
     * @ORM\Column(name="destaque", type="boolean", nullable=false)
     */
    private $destaque;

    /**
     * @var boolean
     *
     * @ORM\Column(name="removido", type="boolean", nullable=false)
     */
    private $removido;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordem", type="integer", nullable=false)
     */
    private $ordem;

    /**
     * @var string
     *
     * @ORM\Column(name="tipounidade", length=50, nullable=false)
     */
    private $tipounidade;

    /**
     * @var string
     *
     * @ORM\Column(name="datacadastro", length=15, nullable=false)
     */
    private $datacadastro;

    /**
     * @var integer
     *
     * @ORM\Column(name="urlvideo", length=255, nullable=false)
     */
    private $urlvideo;

    /**
     * @var integer
     *
     * @ORM\Column(name="frete", type="integer", nullable=false)
     */
    private $frete;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipopedido", type="integer", nullable=false)
     */
    private $tipopedido;
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="Categoria")
	 * @ORM\JoinTable(name="tta_relacionamento_produtos_categorias",
	 *      joinColumns={@ORM\JoinColumn(name="produto", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="categoria", referencedColumnName="id")}
	 *      )
	 */
	protected $categorias;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="Imagem", mappedBy="produto")
	 * @ORM\OrderBy({"destaque"="ASC"})
	 */
	protected $imagens;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getProdutopai()
    {
        return $this->produtopai;
    }

    /**
     * @param int $produtoPai
     */
    public function setProdutopai($produtoPai)
    {
        $this->produtopai = $produtoPai;
        return $this;
    }

    /**
     * @return int
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param int $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return int
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * @param int $marca
     */
    public function setMarca(Marca $marca)
    {
        $this->marca = $marca;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return float
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param float $peso
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
        return $this;
    }

    /**
     * @return float
     */
    public function getLargura()
    {
        return $this->largura;
    }

    /**
     * @param float $largura
     */
    public function setLargura($largura)
    {
        $this->largura = $largura;
        return $this;
    }

    /**
     * @return float
     */
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * @param float $altura
     */
    public function setAltura($altura)
    {
        $this->altura = $altura;
        return $this;
    }

    /**
     * @return float
     */
    public function getComprimento()
    {
        return $this->comprimento;
    }

    /**
     * @param float $comprimento
     */
    public function setComprimento($comprimento)
    {
        $this->comprimento = $comprimento;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorcusto()
    {
        return $this->valorcusto;
    }

    /**
     * @param float $valorCusto
     */
    public function setValorcusto($valorCusto)
    {
        $this->valorcusto = $valorCusto;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorreal()
    {
        return $this->valorreal;
    }

    /**
     * @param float $valorReal
     */
    public function setValorreal($valorReal)
    {
        $this->valorreal = $valorReal;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorvenda()
    {
        return $this->valorvenda;
    }

    /**
     * @param float $valorVenda
     */
    public function setValorvenda($valorVenda)
    {
        $this->valorvenda = $valorVenda;
        return $this;
    }

    /**
     * @return int
     */
    public function getEstoque()
    {
        return $this->estoque;
    }

    /**
     * @param int $estoque
     */
    public function setEstoque($estoque)
    {
        $this->estoque = $estoque;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricaopequena()
    {
        return $this->descricaopequena;
    }

    /**
     * @param string $descricaoPequena
     */
    public function setDescricaopequena($descricaoPequena)
    {
        $this->descricaopequena = $descricaoPequena;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getEspecificacao()
    {
        return $this->especificacao;
    }

    /**
     * @param string $especificacao
     */
    public function setEspecificacao($especificacao)
    {
        $this->especificacao = $especificacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getManual()
    {
        return $this->manual;
    }

    /**
     * @param string $manual
     */
    public function setManual($manual)
    {
        $this->manual = $manual;
        return $this;
    }

    /**
     * @return string
     */
    public function getPalavraschaves()
    {
        return $this->palavraschaves;
    }

    /**
     * @param string $palavraschaves
     */
    public function setPalavraschaves($palavraschaves)
    {
        $this->palavraschaves = $palavraschaves;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getDisponivel()
    {
        return $this->disponivel;
    }

    /**
     * @param boolean $disponivel
     */
    public function setDisponivel($disponivel)
    {
        $this->disponivel = $disponivel;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPromocao()
    {
        return $this->promocao;
    }

    /**
     * @param boolean $promocao
     */
    public function setPromocao($promocao)
    {
        $this->promocao = $promocao;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getLancamento()
    {
        return $this->lancamento;
    }

    /**
     * @param boolean $lancamento
     */
    public function setLancamento($lancamento)
    {
        $this->lancamento = $lancamento;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getDestaque()
    {
        return $this->destaque;
    }

    /**
     * @param boolean $destaque
     */
    public function setDestaque($destaque)
    {
        $this->destaque = $destaque;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getRemovido()
    {
        return $this->removido;
    }

    /**
     * @param boolean $removido
     */
    public function setRemovido($removido)
    {
        $this->removido = $removido;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @param int $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipounidade()
    {
        return $this->tipounidade;
    }

    /**
     * @param string $tipoUnidade
     */
    public function setTipounidade($tipoUnidade)
    {
        $this->tipounidade = $tipoUnidade;
        return $this;
    }

    /**
     * @return int
     */
    public function getDatacadastro()
    {
        return $this->datacadastro;
    }

    /**
     * @param int $dataCadastro
     */
    public function setDatacadastro($dataCadastro)
    {
        $this->datacadastro = $dataCadastro;
        return $this;
    }

    /**
     * @return int
     */
    public function getUrlvideo()
    {
        return $this->urlvideo;
    }

    /**
     * @param int $urlVideo
     */
    public function setUrlvideo($urlVideo)
    {
        $this->urlvideo = $urlVideo;
        return $this;
    }

    /**
     * @return int
     */
    public function getFrete()
    {
        return $this->frete;
    }

    /**
     * @param int $frete
     */
    public function setFrete($frete)
    {
        $this->frete = $frete;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipopedido()
    {
        return $this->tipopedido;
    }

    /**
     * @param int $tipoPedido
     */
    public function setTipopedido($tipoPedido)
    {
        $this->tipopedido = $tipoPedido;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategorias()
    {
        return $this->categorias;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImagens()
    {
        return $this->imagens;
    }
	
    public function toArray()
    {
				$data = parent::toArray();
				if($this->produtopai instanceof AbstractEntity)
					$data['produtopai'] = $this->produtopai->getId() > 0 ? $this->produtopai->toArray() : null;
				if($this->marca instanceof AbstractEntity)
					$data['marca'] = $this->marca->getId() > 0 ? $this->marca->toArray() : null;
				if($this->url instanceof AbstractEntity)
					$data['url'] = $this->url->getId() > 0 ? $this->url->toArray() : null;

				if($this->categorias instanceof \Doctrine\ORM\PersistentCollection){
					$data['categorias'] = [];
					if($this->categorias->count() > 0){
						foreach($this->categorias as $k => $v)
							$data['categorias'][$k] = $v->toArray();
					}
				}
				
				if($this->imagens instanceof \Doctrine\ORM\PersistentCollection){
					$data['imagens'] = [];
					if($this->imagens->count() > 0){
						$destaque	= null;
						foreach($this->imagens as $k => $v){
							if($v->getSessao() === "produtos"){
								$data['imagens'][] = $v->toArray();
								if($v->getDestaque())
									$destaque = $v->toArray();
							}
						}
						if(!empty($destaque))
							$data['imagens']['destaque'] = $destaque;
					}
				}
				
				return $data;
    }

}