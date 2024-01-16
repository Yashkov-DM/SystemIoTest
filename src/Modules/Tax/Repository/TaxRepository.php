<?php

declare(strict_types=1);

namespace App\Modules\Tax\Repository;

use App\Modules\Tax\Entity\Tax;
use App\Repository\EntityRepository;

class TaxRepository extends EntityRepository implements TaxRepositoryInterface
{
    public function getEntityType(): string
    {
        return Tax::class;
    }
}
