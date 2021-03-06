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



    /**
     * @ORM\Column(name="id_entree", type="integer", nullable=true)
     */
    private $entree;

    /**
     * @ORM\Column(name="id_plat", type="integer", nullable=true)
     */
    private $plat;

    /**
     * @ORM\Column(name="id_dessert", type="integer", nullable=true)
     */
    private $dessert;

    /**
     * @ORM\Column(name="id_boisson", type="integer", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="menus", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="CommandeMenu",  mappedBy="menus", cascade={"remove"})
     */
    private $commande;


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


    public function setMenu($entree, $plat, $dessert, $boisson, $prix, $quantite, $user)
    {
        $this->entree = $entree;
        $this->plat = $plat;
        $this->dessert = $dessert;
        $this->boisson = $boisson;
        $this->prix = $prix;
        $this->quantite = $quantite;
        $this->user = $user;

        if($this->entree === null) $this->type = 2;
        else if($this->dessert === null) $this->type = 1;
        else $this->type = 3;

    }

    /**
     * @return mixed
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * @param mixed $commande
     */
    public function setCommande($commande)
    {
        $this->commande = $commande;
    }
}

