<?php

namespace TemTudoAqui;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categoria
 *
 * @ORM\Table(name="tta_produtos_categorias")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class Categoria extends AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \TemTudoAqui\Categoria
     *
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="categorias")
     * @ORM\JoinColumn(name="categoriapai", referencedColumnName="id")
     */
    private $categoriapai;

    /**
     * @var \TemTudoAqui\URL
     *
     * @ORM\ManyToOne(targetEntity="URL")
     * @ORM\JoinColumn(name="url", referencedColumnName="id")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", length=255, nullable=false)
     */
    private $nome;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordem", type="integer", nullable=false)
     */
    private $ordem;

    /**
     * @var string
     *
     * @ORM\Column(name="subreferencia", length=10, nullable=false)
     */
    private $subreferencia;

    /**
     * @var integer
     *
     * @ORM\Column(name="nivel1", type="integer", nullable=false)
     */
    private $nivel1;

    /**
     * @var integer
     *
     * @ORM\Column(name="nivel2", type="integer", nullable=false)
     */
    private $nivel2;

    /**
     * @var integer
     *
     * @ORM\Column(name="nivel3", type="integer", nullable=false)
     */
    private $nivel3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disponivel", type="boolean", nullable=false)
     */
    private $disponivel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visaounica", type="boolean", nullable=false)
     */
    private $visaounica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="home", type="boolean", nullable=false)
     */
    private $home;

    /**
     * @var string
     *
     * @ORM\Column(name="cor", length=20, nullable=false)
     */
    private $cor;

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
     * @ORM\Column(name="imagem", length=255)
     */
    private $imagem;
	
		/**
		 * @var \Doctrine\Common\Collections\ArrayCollection
		 *
		 * @ORM\ManyToMany(targetEntity="Produto")
		 * @ORM\JoinTable(name="tta_relacionamento_produtos_categorias",
		 *      joinColumns={@ORM\JoinColumn(name="categoria", referencedColumnName="id")},
		 *      inverseJoinColumns={@ORM\JoinColumn(name="produto", referencedColumnName="id")}
		 *      )
		 */
		protected $produtos;
	
		/**
		 * @var \Doctrine\Common\Collections\ArrayCollection
		 *
		 * @ORM\OneToMany(targetEntity="Categoria", mappedBy="categoriapai")
		 * @ORM\OrderBy({"nome" = "ASC"})
		 */
		protected $categorias;
	
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
     * @return \TemTudoAqui\Categoria
     */
    public function getCategoriapai()
    {
        return $this->categoriapai;
    }

    /**
     * @param \TemTudoAqui\Categoria $categoriaPai
     */
    public function setCategoriapai(Categoria $categoriaPai)
    {
        $this->categoriapai = $categoriaPai;
        return $this;
    }

    /**
     * @return \TemTudoAqui\URL
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param \TemTudoAqui\URL $url
     */
    public function setUrl(URL $url)
    {
        $this->url = $url;
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
    public function getSubreferencia()
    {
        return $this->subreferencia;
    }

    /**
     * @param string $subReferencia
     */
    public function setSubreferencia($subReferencia)
    {
        $this->subreferencia = $subReferencia;
        return $this;
    }

    /**
     * @return int
     */
    public function getNivel1()
    {
        return $this->nivel1;
    }

    /**
     * @param int $nivel1
     */
    public function setNivel1($nivel1)
    {
        $this->nivel1 = $nivel1;
        return $this;
    }

    /**
     * @return int
     */
    public function getNivel2()
    {
        return $this->nivel2;
    }

    /**
     * @param int $nivel2
     */
    public function setNivel2($nivel2)
    {
        $this->nivel2 = $nivel2;
        return $this;
    }

    /**
     * @return int
     */
    public function getNivel3()
    {
        return $this->nivel3;
    }

    /**
     * @param int $nivel3
     */
    public function setNivel3($nivel3)
    {
        $this->nivel3 = $nivel3;
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
    public function getVisaounica()
    {
        return $this->visaounica;
    }

    /**
     * @param boolean $visaoUnica
     */
    public function setVisaounica($visaoUnica)
    {
        $this->visaounica = $visaoUnica;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * @param boolean $home
     */
    public function setHome($home)
    {
        $this->home = $home;
        return $this;
    }

    /**
     * @return string
     */
    public function getCor()
    {
        return $this->cor;
    }

    /**
     * @param string $cor
     */
    public function setCor($cor)
    {
        $this->cor = $cor;
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
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * @param string $imagem
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProdutos()
    {
        return $this->produtos;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategorias()
    {
        return $this->categorias;
    }
	
		public function toArray()
		{
				$data = parent::toArray();
				if($this->categoriapai instanceof AbstractEntity)
					$data['categoriapai'] = $this->categoriapai->getId() >= 0 && $this->categoriapai->getId() !== '' ? ($this->categoriapai->getId() === 0 ? (new Categoria(['id' => 0]))->toArray() : $this->categoriapai->toArray()) : null;
				if($this->url instanceof AbstractEntity)
					$data['url'] = $this->url->getId() > 0 ? $this->url->toArray() : null;

				return $data;
		}

}