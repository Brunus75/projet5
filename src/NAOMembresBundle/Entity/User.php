<?php
// src/NAOMembresBundle/Entity/User.php

namespace NAOMembresBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="p5_fos_user")
 */
class User extends BaseUser
{
        
    public static $catEntity = "Configuration";
    public static $nameEntity = "Utilisateurs";    
    
    public static $roleIndex = array(0=>"Administrateur",1=>"Naturaliste",2=>"Ornithologue",3=>"Utilisateur");
    public static $indexRole = array("Administrateur"=>0,"Naturaliste"=>1,"Ornithologue"=>2,"Utilisateur"=>3);
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }


}