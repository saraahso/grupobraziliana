<?php

namespace TemTudoAqui;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * Imagem
 *
 * @ORM\Table(name="tta_imagens")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class Imagem extends AbstractEntity {

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
     * @ORM\Column(name="sessao", length=255, nullable=false)
     */
    private $sessao;

    /**
     * @var integer
     *
     * @ORM\Column(name="idsessao", type="integer", nullable=false)
     */
    private $idsessao;

    /**
     * @var string
     *
     * @ORM\Column(name="imagem", length=255, nullable=false)
     */
    private $imagem;

    /**
     * @var string
     *
     * @ORM\Column(name="legenda", length=255, nullable=false)
     */
    private $legenda;

    /**
     * @var boolean
     *
     * @ORM\Column(name="destaque", type="boolean", nullable=false)
     */
    private $destaque;
	
		/**
		 * @var \TemTudoAqui\Produto
		 *
     * @ORM\ManyToOne(targetEntity="Produto", inversedBy="imagens")
     * @ORM\JoinColumn(name="idsessao", referencedColumnName="id")
     */	
		private $produto;
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
    public function getSessao()
    {
        return $this->sessao;
    }

    /**
     * @param string $sessao
     */
    public function setSessao($sessao)
    {
        $this->sessao = $sessao;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdsessao()
    {
        return $this->idsessao;
    }

    /**
     * @param int $idSessao
     */
    public function setIdsessao($idSessao)
    {
        $this->idsessao = $idSessao;
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

}