<?php

declare(strict_types=1);

namespace App\Modules\Price\Payment;

interface PaymentTypeInterface
{
    public function canHandle(string $paymentType): bool;
}
