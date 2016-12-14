<?php
/**
 * Created by PhpStorm.
 * User: jocelynconfrere
 * Date: 14/01/2016
 * Time: 17:37
 */

namespace AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


trait TraitUploadImage
{

    /**
     * @Assert\Image(
     *     maxSize="2M",
     *     mimeTypesMessage = "Le fichier n'est pas une image valide.",
     *     maxSizeMessage = "Votre fichier est trop volumineux, format maximum autorisé {{ limit }}Mo.",
     * )
     */
    private $file = null;

    /******** Upload image ********/

    /**
     * return path of images directory
     * @return string
     */
    abstract public function getUploadDir();

    public function getAblsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir() . '/' . $this->image;
    }

    public function getWebPath()
    {
        return null === $this->image ? null : $this->getUploadDir() . '/' . $this->image;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if(null !== $this->getFile())
        {
            $this->image = sha1(uniqid(mt_rand(), true)) . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */

    public function upload()
    {
        if(null === $this->getFile()) {
            return;
        }

        $this->getFile()->move($this->getUploadRootDir(), $this->image);

        if (isset($this->file)) {
            $this->file = null;
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->file = $this->getAblsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->file)) {
            unlink($this->file);
        }
    }


    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if(null !== $this->file)
        {
            $this->setImage("");
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /******** Upload image end ****/
}