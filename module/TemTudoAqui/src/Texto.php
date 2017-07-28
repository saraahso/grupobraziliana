<?php

namespace TemTudoAqui;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * Texto
 *
 * @ORM\Table(name="tta_textos")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class Texto extends AbstractEntity {

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
     * @ORM\ManyToOne(targetEntity="URL")
     * @ORM\JoinColumn(name="url", referencedColumnName="id")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", length=255, nullable=false)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitulo", length=255, nullable=false)
     */
    private $subtitulo;

    /**
     * @var string
     *
     * @ORM\Column(name="textopequeno", nullable=false)
     */
    private $textopequeno;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", nullable=false)
     */
    private $texto;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Imagem")
     * @ORM\JoinColumn(name="imagem", referencedColumnName="id")
     */
    private $imagem;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordem", type="integer", nullable=false)
     */
    private $ordem;

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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
    public function getSubtitulo()
    {
        return $this->subtitulo;
    }

    /**
     * @param string $subTitulo
     */
    public function setSubtitulo($subTitulo)
    {
        $this->subtitulo = $subTitulo;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextopequeno()
    {
        return $this->textopequeno;
    }

    /**
     * @param string $textoPequeno
     */
    public function setTextopequeno($textoPequeno)
    {
        $this->textopequeno = $textoPequeno;
        return $this;
    }

    /**
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @param string $texto
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
        return $this;
    }

    /**
     * @return int
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * @param int $imagem
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
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
	
		public function toArray()
		{
				$data = parent::toArray();
				if($this->url instanceof AbstractEntity)
					$data['url'] = $this->url->getId() > 0 ? $this->url->toArray() : null;

				return $data;
		}

}