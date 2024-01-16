<?php

declare(strict_types=1);

namespace App\Modules\Price\UseCase;

interface PaymentHandlerInterface
{
    public function handle(string $paymentType, float $price): mixed;
}
