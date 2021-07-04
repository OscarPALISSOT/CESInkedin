<?php

namespace App\DataFixtures;

use App\Entity\Offre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OffreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 150; $i++) {
            $offre = new Offre();
            $offre
                ->setTitre($faker->words($nb = 3, $asText = true))
                ->setEntreprise($faker->company)
                ->setDescription($faker->words($nb = 30, $asText = true))
                ->setVille($faker->city)
                ->setAdresse($faker->streetAddress)
                ->setCodePostal($faker->postcode)
                ->setLat($faker->randomFloat($nbMaxDecimals = 15, $min = 42.3327778, $max = 51.0891667))
                ->setLon($faker->randomFloat($nbMaxDecimals = 15, $min = -4.795555555555556, $max = 8.230555555555556))
                ->setCreator($faker->firstName);
            $manager->persist($offre);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
