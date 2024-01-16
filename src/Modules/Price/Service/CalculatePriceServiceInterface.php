<?php

declare(strict_types=1);

namespace App\Modules\Price\Service;

use App\Modules\Price\Entity\PriceProduct;

interface CalculatePriceServiceInterface
{
    public function getCalculatePrice(PriceProduct $priceProduct): string;
}
