<?php

namespace App\DataFixtures;

use App\Entity\Matiere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $matieres = [
            'HTML/CSS',
            'Javascript',
            'PHP',
            'Bootstrap',
            'Jquery',
            'linux',
            'Docker'
        ];

        foreach ($matieres as $matiereName) {
            $matiere = new Matiere();
            $matiere->setNom($matiereName);
            $manager->persist($matiere);
        }


        $manager->flush();
    }
}
