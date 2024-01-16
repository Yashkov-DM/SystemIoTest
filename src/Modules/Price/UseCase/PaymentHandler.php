<?php

declare(strict_types=1);

namespace App\Modules\Price\UseCase;

use App\Modules\Price\Resolver\PaymentTypeResolverInterface;

readonly class PaymentHandler implements PaymentHandlerInterface
{
    public function __construct(
        private PaymentTypeResolverInterface $paymentResolver,
    ) {
    }

    public function handle(string $paymentType, float $price): mixed
    {
        $importImport = $this->paymentResolver->getPaymentService($paymentType);

        return $importImport->payment($price);
    }
}
