<?php

declare(strict_types=1);

namespace App\Modules\Coupon\Repository;

use App\Modules\Coupon\Entity\Coupon;
use App\Repository\EntityRepository;

class CouponRepository extends EntityRepository implements CouponRepositoryInterface
{
    public function getEntityType(): string
    {
        return Coupon::class;
    }
}
