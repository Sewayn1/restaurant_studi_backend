<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use Exception;
use App\Entity\Restaurant;

//--------------------------------------------//

class RestaurantFixtures extends Fixture
{
    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {

            $restaurant = (new Restaurant())
                ->setName("Restaurant $i")
                ->setDescription("Description du Restaurant $i")
                ->setMaxGuest(random_int(10, 50))
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($restaurant);
            $this->addReference("restaurant$i", $restaurant);
        }
        $manager->flush();
    }
}
