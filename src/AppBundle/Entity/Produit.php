<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TraitUploadImage;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 */
class Produit
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePeremption", type="date")
     *
     */
    private $datePeremption;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     * @Assert\NotBlank()
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     * @Assert\NotBlank()
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Categorie", inversedBy="produits", cascade={"persist"})
     */
    private $categories;

    private $cat;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Allergene", inversedBy="produits", cascade={"persist"})
     */
    private $allergenes;

    private $all;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="entree")
     * @ORM\JoinColumn(name="menu", referencedColumnName="id")
     */
    private $menuEntree;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="plat")
     * @ORM\JoinColumn(name="menu", referencedColumnName="id")
     */
    private $menuPlat;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="dessert")
     * @ORM\JoinColumn(name="menu", referencedColumnName="id")
     */
    private $menuDessert;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="boisson")
     * @ORM\JoinColumn(name="menu", referencedColumnName="id")
     */
    private $menuBoisson;


    use TraitUploadImage;
    public function getUploadDir()
    {
        return 'produits';
    }

    /**
     * Add categorie
     *
     * @param \AppBundle\Entity\Categorie $categorie
     *
     * @return Produit
     */
    public function addCategorie(\AppBundle\Entity\Categorie $categorie)
    {
        $this->categories[] = $categorie;

        return $this;
    }

    /**
     * Remove categorie
     *
     * @param \AppBundle\Entity\Categorie $categorie
     */
    public function removeCategorie(\AppBundle\Entity\Categorie $categorie)
    {
        $this->categories->removeElement($categorie);
    }


    /**
     * Add allergene
     *
     * @param \AppBundle\Entity\Categorie $categorie
     *
     * @return Produit
     */
    public function addAllergene(\AppBundle\Entity\Allergene $allergene)
    {
        $this->allergenes[] = $allergene;

        return $this;
    }

    /**
     * Remove allergene
     *
     * @param \AppBundle\Entity\Categorie $categorie
     */
    public function removeAllergene(\AppBundle\Entity\Allergene $allergene)
    {
        $this->allergenes->removeElement($allergene);
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
     * @return Produit
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
     * Set description
     *
     * @param string $description
     *
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set allergenes
     *
     * @param string $allergenes
     *
     * @return Produit
     */
    public function setAllergenes($allergenes)
    {
        $this->allergenes = $allergenes;

        return $this;
    }

    /**
     * Get allergenes
     *
     * @return mixed
     */
    public function getAllergenes()
    {
        return $this->allergenes;
    }

    /**
     * Set datePeremption
     *
     * @param \DateTime $datePeremption
     *
     * @return Produit
     */
    public function setDatePeremption($datePeremption)
    {
        $this->datePeremption = $datePeremption;

        return $this;
    }

    /**
     * Get datePeremption
     *
     * @return \DateTime
     */
    public function getDatePeremption()
    {
        return $this->datePeremption;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Produit
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
     * Set image
     *
     * @param string $image
     *
     * @return Produit
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * @param mixed $cat
     */
    public function setCat($cat)
    {
        $this->cat = $cat;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * @param mixed $all
     */
    public function setAll($all)
    {
        $this->all = $all;
    }

    /**
     * @return mixed
     */
    public function getMenuEntree()
    {
        return $this->menuEntree;
    }

    /**
     * @param mixed $menuEntree
     */
    public function setMenuEntree($menuEntree)
    {
        $this->menuEntree = $menuEntree;
    }

    /**
     * @return mixed
     */
    public function getMenuPlat()
    {
        return $this->menuPlat;
    }

    /**
     * @param mixed $menuPlat
     */
    public function setMenuPlat($menuPlat)
    {
        $this->menuPlat = $menuPlat;
    }

    /**
     * @return mixed
     */
    public function getMenuDessert()
    {
        return $this->menuDessert;
    }

    /**
     * @param mixed $menuDessert
     */
    public function setMenuDessert($menuDessert)
    {
        $this->menuDessert = $menuDessert;
    }

    /**
     * @return mixed
     */
    public function getMenuBoisson()
    {
        return $this->menuBoisson;
    }

    /**
     * @param mixed $menuBoisson
     */
    public function setMenuBoisson($menuBoisson)
    {
        $this->menuBoisson = $menuBoisson;
    }
}

