<?php

declare(strict_types=1);

namespace App\Modules\Product\Repository;

use App\Modules\Product\Entity\Product;
use App\Repository\EntityRepository;

class ProductRepository extends EntityRepository implements ProductRepositoryInterface
{
    public function getEntityType(): string
    {
        return Product::class;
    }
}
