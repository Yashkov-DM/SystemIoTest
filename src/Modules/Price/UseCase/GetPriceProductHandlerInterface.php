<?php

declare(strict_types=1);

namespace App\Modules\Price\UseCase;

use App\Modules\Price\Response\CalculatePriceResponse;
use App\System\RequestInterface;

interface GetPriceProductHandlerInterface
{
    public function handle(RequestInterface $request): CalculatePriceResponse;
}
