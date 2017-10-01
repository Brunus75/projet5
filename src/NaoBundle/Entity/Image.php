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
        // S'il n'y a pas de fichier (champ optionnel), nous ne faisons rien
        if (null === $this->file) {
            return;
        }
        // Le nom du fichier est son identifiant, il suffit de stocker son extension
        $this->ext = $this->file->guessExtension();
        // Et nous générons l'attribut alt de la balise <img>, à la valeur du nom de fichier sur l de l'utilisateur
        $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // S'il n'y a pas de fichier (champ optionnel), nous ne faisons rien
        if (null === $this->file) {
            return;
        }
        // Si nous avons un ancien fichier (attribut tempFilename non nul), nous le supprimons
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
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // Le nom du fichier est temporairement sauvegardé car il dépend de l'ID
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->ext;
    }
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // Dans PostRemove, nous n'avons pas accès à l'identifiant, nous utilisons notre nom enregistré
        if (file_exists($this->tempFilename)) {
            // Nous supprimons le fichier
            unlink($this->tempFilename);
        }
    }
    public function getUploadDir()
    {
        // Dans PostRemove, nous n'avons pas accès à l'identifiant, nous utilisons notre nom enregistré
        return 'uploads/img';
    }
    protected function getUploadRootDir()
    {
        // Nous renvoyons le chemin relatif à l'image
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }
    public function getWebPath()
    {
        // Nous construisons le chemin du Web
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
        // Nous vérifions si nous avions déjà un fichier pour cette entité
        if (null !== $this->ext) {
            // Nous sauvegardons l'extension de fichier pour la supprimer plus tard
            $this->tempFilename = $this->ext;
        // Les valeurs des attributs url et alt sont réinitialisées            $this->ext = null;
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

