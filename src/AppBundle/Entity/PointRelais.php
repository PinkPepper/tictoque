<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PointRelais
 *
 * @ORM\Table(name="point_relais")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PointRelaisRepository")
 */
class PointRelais
{

    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;


    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank()
     */
    private $adresse;

    /**
     * @var int
     * @ORM\OneToMany(targetEntity="Produit", mappedBy="pointRelais", cascade={"remove"})
     */
    private $produits;

    /**
     * @var int
     **@ORM\ManyToOne(targetEntity="User", inversedBy="pointsRelais")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     * @ORM\OneToMany(targetEntity="Livraison", mappedBy="pointRelais", cascade={"persist"})
     */
    private $livraison;

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return PointRelais
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return PointRelais
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return int
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @param int $produits
     */
    public function setProduits($produits)
    {
        $this->produits = $produits;
    }

    /**
     * @return int
     */
    public function getPointRelais()
    {
        return $this->pointRelais;
    }

    /**
     * @param int $pointRelais
     */
    public function setPointRelais($pointRelais)
    {
        $this->pointRelais = $pointRelais;
    }

    /**
     * @return int
     */
    public function getLivraison()
    {
        return $this->livraison;
    }

    /**
     * @param int $livraison
     */
    public function setLivraison($livraison)
    {
        $this->livraison = $livraison;
    }
}

