<?php

namespace App\DataFixtures;

use App\Modules\Tax\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxFixtures extends Fixture
{
    private const TAX_LIST = [
        'DE' => [
            'country' => 'Германия',
            'percent' => 19,
            'number' => 'XXXXXXXXX',
        ],
        'IT' => [
            'country' => 'Италия',
            'percent' => 22,
            'number' => 'XXXXXXXXXXX',
        ],
        'FR' => [
            'country' => 'Франции',
            'percent' => 20,
            'number' => 'YYXXXXXXXXX',
        ],
        'GR' => [
            'country' => 'Греции',
            'percent' => 24,
            'number' => 'XXXXXXXXX',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TAX_LIST as $code => $tax) {
            $product = new Tax();
            $product->setCode($code);
            $product->setCountry($tax['country']);
            $product->setPercent($tax['percent']);
            $product->setCodeNumber($tax['number']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
