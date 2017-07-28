<?php

namespace TemTudoAqui\Banner;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * URL
 *
 * @ORM\Table(name="tta_banners")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class Banner extends \TemTudoAqui\AbstractEntity {

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
     * @ORM\Column(name="titulo", length=255, nullable=false)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="datainicio", length=20, nullable=false)
     */
    private $datainicio;

    /**
     * @var string
     *
     * @ORM\Column(name="datafim", length=20, nullable=false)
     */
    private $datafim;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", nullable=false)
     */
    private $clicks;

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
     * @var float
     *
     * @ORM\Column(name="largura", type="float", scale=6, precision=2, nullable=false)
     */
    private $largura;

    /**
     * @var float
     *
     * @ORM\Column(name="altura", type="float", scale=6, precision=2, nullable=false)
     */
    private $altura;

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
     * @ORM\JoinTable(name="tta_relacionamento_banners_categorias",
     *      joinColumns={@ORM\JoinColumn(name="banner", referencedColumnName="id")},
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
    public function getDatainicio()
    {
        return $this->datainicio;
    }

    /**
     * @param string $datainicio
     */
    public function setDatainicio($datainicio)
    {
        $this->datainicio = $datainicio;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatafim()
    {
        return $this->datafim;
    }

    /**
     * @param string $datafim
     */
    public function setDatafim($datafim)
    {
        $this->datafim = $datafim;
        return $this;
    }

    /**
     * @return int
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * @param int $clicks
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
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