<?php

namespace TemTudoAqui\Mailing;

use Doctrine\ORM\Mapping as ORM,
  Zend\Stdlib\Hydrator;

/**
 * URL
 *
 * @ORM\Table(name="tta_mailing_pacotes")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\Mailing\PacoteRepository")
 */
class Pacote extends \TemTudoAqui\AbstractEntity
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
   * @var string
   *
   * @ORM\Column(name="titulo", length=50, nullable=false)
   */
  private $titulo;

  /**
   * @var \Doctrine\Common\Collections\ArrayCollection
   *
   * @ORM\OneToMany(targetEntity="Email", mappedBy="pacote")
   */
  protected $emails;

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
  public function getEmails()
  {
    return $this->emails;
  }

}