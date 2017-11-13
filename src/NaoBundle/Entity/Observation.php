<?php

namespace NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Observation
 *
 * @ORM\Table(name="observation")
 * @ORM\Entity(repositoryClass="NaoBundle\Repository\ObservationRepository")
 */
class Observation
{
    const STATUS_ATTENTE = 'attente';
    const STATUS_ACCEPTE = 'accepte';
    const STATUS_REJET = 'rejet';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="NAOMembresBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @Assert\DateTime()
     * @Assert\NotBlank(message = "Veuillez renseigner la date et l'heure")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=64, nullable=false, columnDefinition="ENUM('attente', 'accepte', 'rejet')", options={"default":"attente"})
     */
    private $statut;

    /**
     *
     * @ORM\ManyToOne(targetEntity="NaoBundle\Entity\Especes", cascade={"persist"})
     * @ORM\JoinColumn(name="especes_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    private $oiseau;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max=250)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="NaoBundle\Entity\Image", cascade={"persist", "remove"})
     * @assert\Valid()
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Assert\NotBlank(message = "Veuillez renseigner ce champ")
     * @Assert\Type(type="float", message="coordonnee invalide")
     * @Assert\Range(
     *      min = -90.0,
     *      max = 90.0,
     *      minMessage = "La latitude ne peut être inférieure à -90°",
     *      maxMessage = "La latitude ne peu être supérieure à 90°"
     * )
     *
     */
    
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Assert\NotBlank(message = "Veuillez renseigner ce champ")
     * @Assert\Type(type="float", message="coordonnée invalide")
     *
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="text", nullable=false)
     * @Assert\Type("string")
     * @Assert\Length(max=50)
     */
    private $ville;



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
     * Set user
     *
     * @param integer $user
     *
     * @return Observation
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Observation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Observation
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set oiseau
     *
     * @param string $oiseau
     *
     * @return Observation
     */
    public function setOiseau($oiseau)
    {
        $this->oiseau = $oiseau;

        return $this;
    }

    /**
     * Get oiseau
     *
     * @return string
     */
    public function getOiseau()
    {
        return $this->oiseau;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Observation
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


    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }


    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Observation
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
     * Set ville
     *
     * @param string $ville
     *
     * @return Observation
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

}

