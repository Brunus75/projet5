<?php

namespace NaoBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact
 *
 */
class Contact
{
    /**
     * @var string
     *
     * @Assert\NotBlank("message" => "Veuillez remplir le champ nom.")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le prénom doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "Le prénom doit faire moins de {{ limit }} caractères"
     * )
     *
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank("message" => "Veuillez remplir le champ prénom.")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le nom doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "Le nom doit faire moins de {{ limit }} caractères"
     * )
     *
     */
    private $firstname;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Vous devez entrer une adresse e-mail.")
     * @Assert\Email(
     *     message = "Cette adresse E-mail n'est pas valide.",
     *     checkMX = true
     * )
     *
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank("message" => "Veuillez inscrire un objet.")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "L'objet du message doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "L'objet du message doit faire moins de {{ limit }} caractères"
     * )
     */
    private $object;

    /**
     * @var string
     *
     * @Assert\NotBlank("message" => "Veuillez inscrire un message.")
     * @Assert\Length(
     *      min = 5,
     *      max = 400,
     *      minMessage = "Le message doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "Le message doit faire moins de {{ limit }} caractères."
     * )
     */
    private $message;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstname
     *
     * @param string $name
     *
     * @return Contact
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set object
     *
     * @param string $object
     *
     * @return Contact
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Contact
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}

