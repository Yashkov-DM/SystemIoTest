<?php

declare(strict_types=1);

namespace App\Modules\Price\Resolver;

interface PaymentTypeResolverInterface
{
    public function getPaymentService(string $paymentType);
}
