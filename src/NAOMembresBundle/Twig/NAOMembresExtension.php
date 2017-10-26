<?php

// src/CMS/AdminBundle/Twig/CMSAdminExtension.php

namespace NAOMembresBundle\Twig;

use NAOMembresBundle\Entity\User as User;

class NAOMembresExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('prix', array($this, 'prixFilter')),
            new \Twig_SimpleFilter('ceil', array($this, 'ceilFilter')),
            new \Twig_SimpleFilter('ksort', array($this, 'KsortFilter')),
            new \Twig_SimpleFilter('role', array($this, 'roleFilter')),
            new \Twig_SimpleFilter('urlEncode', array($this, 'urlEncode')),
        );
    }

    private function optionsRole($value, $tabRole) {
        $return = "";
        strtok($value, '_');
        $value = strtok('_'); 
        $value = ucfirst(strtolower($value));
        $value = User::$indexRole[$value];
        
        foreach ($tabRole as $hierachie => $role) {
            
            if ($hierachie == $value) {
                $return .= "<option selected value='$hierachie'>" . $role . "</option>";
            } else {
                $return .= "<option value='$hierachie'>$role</option>";
            }
        }
        return $return;
    }

    public function urlEncode($str) {
        $str = str_replace(' ', '-', $str);
        $str = htmlentities($str, ENT_NOQUOTES, "utf-8");
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
        return $str;
    }

    public function prixFilter($number, $decimals = 2, $decPoint = ',', $thousandsSep = '') {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price . "€";

        return $price;
    }

    public function ceilFilter($number, $decimals = 1, $decPoint = '.', $thousandsSep = '') {
        return number_format($number, $decimals, $decPoint, $thousandsSep);
    }

    public function KsortFilter($tab) {
        ksort($tab);
        return $tab;
    }

    public function roleFilter($value, $P_nomEntity, $nomChamps, $id) {
        $tabRole = User::$roleIndex;
        strtok($P_nomEntity, ':');
        $nomEntity = strtok(':');
        $return = "<select id='$id' name='" . $nomEntity . '[' . $nomChamps . "]'>";
        $return .= $this->optionsRole($value, $tabRole);
        $return .= "</select>";
        return $return;
    }

    public function getName() {
        return 'CMSAdmin_extension';
    }

}
