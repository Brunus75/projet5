<?php

namespace NaoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NaoBundle\Entity\Especes;

class LoadEspeces implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $especesCsv = fopen(dirname(__FILE__).'/Resources/especes.csv', 'r');
        $i = 0;
        while(!feof($especesCsv)) {
            $line = fgetcsv($especesCsv, 600, ';');

            if($i > 0){
                $especes[$i] = new Especes();
                $especes[$i]->setOrdre($line[0]);
                $especes[$i]->setFamille($line[1]);
                $especes[$i]->setCdNom($line[2]);
                $especes[$i]->setLbNom($line[3]);
                $especes[$i]->setNomVern($line[4]);
                $especes[$i]->setHabitat($line[5]);
                $especes[$i]->setStatut($line[6]);
                $especes[$i]->setUrl($line[7]);

                if($especes[$i]->getCdNom() != null){
                    $manager->persist($especes[$i]);
                }
            }

            // FLUSH toutes les 25 persistances pour améliorer les performances de chargement
            if($i % 25 == 0){
                $manager->flush();
                $manager->clear();
            }

            $i = $i + 1;
        }
        fclose($especesCsv);

        $manager->flush();
    }

    /**
    +     * Get the order of this fixture
    +     * @return integer
    +     */
    public function getOrder()
    {
        return 2;
    }
}