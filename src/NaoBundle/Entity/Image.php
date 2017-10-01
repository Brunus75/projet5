<?php

namespace NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
     * @ORM\Column(name="ext", type="string", length=255)
     * @Assert\Length(max=4)
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     * @Assert\Type("string")
     */
    private $alt;

    /**
     * @var UploadedFile
     * @Assert\Valid()
     * @Assert\File(
     *     mimeTypes={ "image/jpeg", "image/jpg", "image/png", "image/gif" },
     *     mimeTypesMessage = "This file is not a valid image."
     * )
     */
    private $file;

    private $tempFilename;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // If there is no file (optional field), we do nothing
        if (null === $this->file) {
            return;
        }
        // The name of the file is its id, we have just to store its extension
        $this->ext = $this->file->guessExtension();
        // And we generate the alt attribute of the <img> tag, to the value of the file name on the user's PC
        $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // If there is no file (optional field), we do nothing
        if (null === $this->file) {
            return;
        }
        // If we had an old file (attribute tempFilename not null), we delete it
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }


        $image = new ImageResize($this->file);
        $image->resizeToHeight(600);
        $fileName = $this->id.'.'.$this->ext;
        if ($this->ext = 'png') {
            $image->save($this->getUploadDir() . '/' . $fileName, IMAGETYPE_PNG, 2);
        } elseif ($this->ext = 'jpeg' || $this->ext = 'jpg' ) {
            $image->save($this->getUploadDir() . '/' . $fileName, IMAGETYPE_JPEG, 75);
        } elseif ($this->ext = 'gif') {
            $image->save($this->getUploadDir() . '/' . $fileName,IMAGETYPE_GIF, 50);
        }

        /*
        // We move the file sent in the directory of our choice
        $this->file->move(
            $this->getUploadRootDir(), // The destination directory
            $this->id.'.'.$this->ext   // The name of the file to create, here "id.extension"
        );*/
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // The file name is temporarily saved because it depends on the id
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->ext;
    }
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // In PostRemove, we do not have access to the id, we use our saved name
        if (file_exists($this->tempFilename)) {
            // We delete the file
            unlink($this->tempFilename);
        }
    }
    public function getUploadDir()
    {
        // In PostRemove, we do not have access to the id, we use our saved name
        return 'uploads/img';
    }
    protected function getUploadRootDir()
    {
        // We return the relative path to the image
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }
    public function getWebPath()
    {
        // We build the web path
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getExt();
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
     * Set extension
     *
     * @param string $ext
     *
     * @return Image
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        // We check if we already had a file for this entity
        if (null !== $this->ext) {
            // We save the file extension to delete it later
            $this->tempFilename = $this->ext;
            // The values ​​of the url and alt attributes are reset
            $this->ext = null;
            $this->alt = null;
        }
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


}

