<?php

namespace TemTudoAqui\Slide;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * URL
 *
 * @ORM\Table(name="tta_slides")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class Slide extends \TemTudoAqui\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", length=50, nullable=false)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="legenda", length=255, nullable=false)
     */
    private $legenda;

    /**
     * @var string
     *
     * @ORM\Column(name="enderecourl", length=255, nullable=false)
     */
    private $enderecourl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ativo", type="boolean", nullable=false)
     */
    private $ativo;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", length=20, nullable=false)
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordem", type="integer", nullable=false)
     */
    private $ordem;

    /**
     * @var integer
     *
     * @ORM\Column(name="segundos", type="integer", nullable=false)
     */
    private $segundos;

    /**
     * @var string
     *
     * @ORM\Column(name="corfundo", length=7, nullable=false)
     */
    private $corfundo;

    /**
     * @var string
     *
     * @ORM\Column(name="imagem", length=255, nullable=false)
     */
    private $imagem;

    /**
     * @var string
     *
     * @ORM\Column(name="flash", length=255, nullable=false)
     */
    private $flash;
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="Categoria")
	 * @ORM\JoinTable(name="tta_relacionamento_slides_categorias",
	 *      joinColumns={@ORM\JoinColumn(name="slide", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="categoria", referencedColumnName="id")}
	 *      )
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
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        return $this;
    }

    /**
     * @return string
     */
    public function getLegenda()
    {
        return $this->legenda;
    }

    /**
     * @param string $legenda
     */
    public function setLegenda($legenda)
    {
        $this->legenda = $legenda;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnderecourl()
    {
        return $this->enderecourl;
    }

    /**
     * @param string $enderecourl
     */
    public function setEnderecourl($enderecourl)
    {
        $this->enderecourl = $enderecourl;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param boolean $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return integer
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @param integer $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * @return integer
     */
    public function getSegundos()
    {
        return $this->segundos;
    }

    /**
     * @param integer $segundos
     */
    public function setSegundos($segundos)
    {
        $this->segundos = $segundos;
        return $this;
    }

    /**
     * @return string
     */
    public function getCorfundo()
    {
        return $this->corfundo;
    }

    /**
     * @param string $corfundo
     */
    public function setCorfundo($corfundo)
    {
        $this->corfundo = $corfundo;
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
     * @return string
     */
    public function getFlash()
    {
        return $this->flash;
    }

    /**
     * @param string $flash
     */
    public function setFlash($flash)
    {
        $this->flash = $flash;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategorias()
    {
        return $this->categorias;
    }

}