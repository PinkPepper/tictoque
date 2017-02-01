<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuRepository")
 */
class Menu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

//    /**
//     * @ORM\OneToMany(targetEntity="Produit", mappedBy="menuEntree", cascade={"persist"})
//     */
//    private $entree;
//
//    /**
//     * @ORM\OneToMany(targetEntity="Produit", mappedBy="menuPlat", cascade={"persist"})
//     */
//    private $plat;
//
//    /**
//     * @ORM\OneToMany(targetEntity="Produit", mappedBy="menuDessert", cascade={"persist"})
//     */
//    private $dessert;
//
//    /**
//     * @ORM\OneToMany(targetEntity="Produit", mappedBy="menuBoisson", cascade={"persist"})
//     */
//    private $boisson;

    /**
     * @ORM\Column(name="entree", nullable=true)
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Produit", cascade={"persist"})
     */
    private $entree;

    /**
     * @ORM\Column(name="plat", nullable=true)
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Produit", cascade={"persist"})
     */
    private $plat;

    /**
     * @ORM\Column(name="dessert", nullable=true)
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Produit", cascade={"persist"})
     */
    private $dessert;

    /**
     * @ORM\Column(name="boisson", nullable=true)
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Produit", cascade={"persist"})
     */
    private $boisson;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     *  @ORM\ManyToMany(targetEntity="Panier", inversedBy="menus", cascade={"persist"})
     *  @ORM\JoinTable(name="RelationMenuPanier",
     *  joinColumns={@ORM\JoinColumn(name="panier_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="menu_id", referencedColumnName="id")})
     */
    private $paniers;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="menus", cascade={"persist"})
     */
    private $user;


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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Menu
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Menu
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @return mixed
     */
    public function getEntree()
    {
        return $this->entree;
    }

    /**
     * @param mixed $entree
     */
    public function setEntree($entree)
    {
        $this->entree = $entree;
    }

    /**
     * @return mixed
     */
    public function getPlat()
    {
        return $this->plat;
    }

    /**
     * @param mixed $plat
     */
    public function setPlat($plat)
    {
        $this->plat = $plat;
    }

    /**
     * @return mixed
     */
    public function getDessert()
    {
        return $this->dessert;
    }

    /**
     * @param mixed $dessert
     */
    public function setDessert($dessert)
    {
        $this->dessert = $dessert;
    }

    /**
     * @return mixed
     */
    public function getBoisson()
    {
        return $this->boisson;
    }

    /**
     * @param mixed $boisson
     */
    public function setBoisson($boisson)
    {
        $this->boisson = $boisson;
    }

    /**
     * @return mixed
     */
    public function getPaniers()
    {
        return $this->paniers;
    }

    /**
     * @param mixed $paniers
     */
    public function setPaniers(Panier $paniers = null)
    {
        $this->paniers = $paniers;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function __toString()
    {
        return '' . $this->id;
    }
}

