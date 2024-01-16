<?php

namespace App\DataFixtures;

use App\Modules\Coupon\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    private const DISCOUNT_LIST = [
        'D' => 'percent',
        'DF' => 'fixed',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DISCOUNT_LIST as $code => $name) {
            $coupon = new Coupon();
            $coupon->setName($name);
            $coupon->setCode($code);

            $manager->persist($coupon);
        }

        $manager->flush();
    }
}
