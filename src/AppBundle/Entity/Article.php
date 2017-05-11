<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TraitUploadImage;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * Gère la date de création et de modification
     */
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
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     * @Assert\NotBlank()
     */
    private $contenu;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="metadescription", type="text", nullable=true)
     */
    private $metadescription;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articles")
     * @ORM\JoinColumn(name="auteur", referencedColumnName="id")
     */
    private $auteur;

    /**
     * @var int
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="article", cascade={"remove"})
     */
    private $commentaires;

    /**
     * Pour obtenir un titre "je suis un titre" en "je-suis-un-titre"
     * Utile pour le référencement, dans l'url
     *
     * @var string
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime $contentChanged
     *
     * @ORM\Column(name="content_changed", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"titre", "contenu"})
     */
    private $contentChanged;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;


    use TraitUploadImage;
    public function getUploadDir()
    {
        return 'articles';
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
     * Set image
     *
     * @param string $image
     *
     * @return Article
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


    public function __toString()
    {
        return "" . $this->id;
    }

    /**
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @param string $contenu
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getMetadescription()
    {
        return $this->metadescription;
    }

    /**
     * @param string $metadescription
     */
    public function setMetadescription($metadescription)
    {
        $this->metadescription = $metadescription;
    }

    /**
     * @return int
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param int $auteur
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return \DateTime
     */
    public function getContentChanged()
    {
        return $this->contentChanged;
    }

    /**
     * @param \DateTime $contentChanged
     */
    public function setContentChanged($contentChanged)
    {
        $this->contentChanged = $contentChanged;
    }

}

