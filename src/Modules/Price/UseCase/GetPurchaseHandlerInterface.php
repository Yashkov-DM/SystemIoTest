<?php

declare(strict_types=1);

namespace App\Modules\Price\UseCase;

use App\Modules\Price\Response\PaymentPriceResponse;
use App\System\RequestInterface;

interface GetPurchaseHandlerInterface
{
    public function handle(RequestInterface $request): PaymentPriceResponse;
}
