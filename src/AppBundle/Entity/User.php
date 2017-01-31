<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TraitUploadImage;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"telephone", "email"})
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="cb", type="string", length=255, nullable=true)
     */
    private $cb;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $telephone;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * bloqué ou non
     */
    protected $enabled;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="auteur", cascade={"remove"})
     *
     */
    protected $articles;

    /**
     * @var int
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="auteur", cascade={"remove"})
     */
    private $commentaires;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Panier", inversedBy="client", cascade={"persist"})
     * @ORM\JoinTable(name="RelationPanierClient",
     *  joinColumns={@ORM\JoinColumn(name="panier_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")})
     */
    private $panier;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Allergene", inversedBy="users", cascade={"persist"})
     */
    private $allergenes;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="user")
     * @ORM\JoinColumn(name="menu", referencedColumnName="id")
     */
    private $menus;

    use TraitUploadImage;
    public function getUploadDir()
    {
        return 'users/';
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
     * @return User
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set cb
     *
     * @param string $cb
     *
     * @return User
     */
    public function setCb($cb)
    {
        $this->cb = $cb;

        return $this;
    }

    /**
     * Get cb
     *
     * @return string
     */
    public function getCb()
    {
        return $this->cb;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return User
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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return int
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * @param int $commentaires
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }

    /**
     * @return mixed
     */
    public function getPanier()
    {
        return $this->panier;
    }

    /**
     * @param mixed $panier
     */
    public function setPanier($panier)
    {
        $this->panier = $panier;
    }

    /**
     * @return mixed
     */
    public function getAllergenes()
    {
        return $this->allergenes;
    }

    /**
     * @param mixed $allergenes
     */
    public function setAllergenes($allergenes)
    {
        $this->allergenes = $allergenes;
    }

    /**
     * @return mixed
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @param mixed $menus
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;
    }
}

