<?php

namespace NAOMembresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use NAOMembresBundle\Entity\User as User;


/**
 * role
 *
 * @ORM\Table(name="p5_role")
 * @ORM\Entity
 * @UniqueEntity(fields="nomEntity", message="Entity déjà defini")
 */
class Role
{
     
    public static $catEntity = "Configuration";
    public static $nameEntity = "Rôle";
        
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

  
    
    /**
     * @var string
     *
     * @ORM\Column(name="nomEntity", type="string", length=255)
     */
    private $nomEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="modification", type="integer")
     */
    private $modification;

    /**
     * @var string
     *
     * @ORM\Column(name="creation", type="integer")
     */
    private $creation;

    /**
     * @var string
     *
     * @ORM\Column(name="suppression", type="integer")
     */
    private $suppression;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    private function getNomRole($P_role){
        $role = $P_role;
        $role = User::$roleIndex[$role];
        $role = "ROLE_".strtoupper($role);
        return $role;
    }

    /**
     * Set Nom Entity
     *
     * @param string $nomEntity
     * @return role
     */
    public function setNomEntity($nomEntity)
    {
        $this->nomEntity = $nomEntity;
    
        return $this;
    }

    /**
     * Get Categorie Entity
     *
     * @return string 
     */
    public function getCatEntity()
    {
        return $this->catEntity;
    }

    /**
     * Set Categorie Entity
     *
     * @param string $catEntity
     * @return role
     */
    public function setCatEntity($catEntity)
    {
        $this->catEntity = $catEntity;
    
        return $this;
    }

    /**
     * Get Nom Entity
     *
     * @return string 
     */
    public function getNomEntity()
    {
        return $this->nomEntity;
    }


    /**
     * Set modification
     *
     * @param integer $modification
     * @return role
     */
    public function setModification($modification)
    {
        $this->modification = $modification;
    
        return $this;
    }

    /**
     * Get modification
     *
     * @return integer
     */
    public function getModification()
    {
        return $this->getNomRole($this->modification);
    }

    /**
     * Set creation
     *
     * @param integer $creation
     * @return clients
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;
    
        return $this;
    }

    /**
     * Get creation
     *
     * @return integer
     */
    public function getCreation()
    {
        return $this->getNomRole($this->creation);
    }

    /**
     * Set suppression
     *
     * @param integer $suppression
     * @return clients
     */
    public function setSuppression($suppression)
    {
        $this->suppression = $suppression;
    
        return $this;
    }

    /**
     * Get suppression
     *
     * @return string 
     */
    public function getSuppression()
    {
        return $this->getNomRole($this->suppression);
    }

}
