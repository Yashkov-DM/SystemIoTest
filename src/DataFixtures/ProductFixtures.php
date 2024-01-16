<?php

namespace App\DataFixtures;

use App\Modules\Product\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private const CURRENCY = 'EUR';
    private const PRODUCT_LIST = [
        'Iphone' => 100,
        'Наушники' => 20,
        'Чехол' => 10
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PRODUCT_LIST as $name => $price) {
            $product = new Product();
            $product->setName($name);
            $product->setPrice($price);
            $product->setCurrency(self::CURRENCY);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
