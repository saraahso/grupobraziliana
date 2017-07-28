<?php

namespace TemTudoAqui\Banner;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * URL
 *
 * @ORM\Table(name="tta_banners_categorias")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class Categoria extends \TemTudoAqui\AbstractEntity {

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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Banner")
     * @ORM\JoinTable(name="tta_relacionamento_banners_categorias",
     *      joinColumns={@ORM\JoinColumn(name="categoria", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="banner", referencedColumnName="id")}
     *      )
     */
    protected $banners;
	
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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBanners()
    {
        return $this->banners;
    }

}