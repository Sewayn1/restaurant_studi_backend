<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use App\Entity\Restaurant;
use Exception;
use App\Entity\Picture;

//--------------------------------------------//

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {

            $picture = (new Picture())
                ->setTitle("Picture numéro $i")
                ->setSlug("slug-article-title")
                ->setRestaurant($this->getReference("restaurant" .random_int(1, 20), Restaurant::class))
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($picture);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [RestaurantFixtures::class];
    }
}
