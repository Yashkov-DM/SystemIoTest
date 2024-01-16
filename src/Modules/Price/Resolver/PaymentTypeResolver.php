<?php

declare(strict_types=1);

namespace App\Modules\Price\Resolver;

readonly class PaymentTypeResolver implements PaymentTypeResolverInterface
{
    public function __construct(private iterable $paymentTypeList)
    {
    }

    public function getPaymentService(string $paymentType)
    {
        foreach ($this->paymentTypeList as $paymentHandler) {
            if ($paymentHandler->canHandle($paymentType)) {
                return $paymentHandler;
            }
        }

        throw new \RuntimeException(\sprintf('No payment by type %s', $paymentType));
    }
}
