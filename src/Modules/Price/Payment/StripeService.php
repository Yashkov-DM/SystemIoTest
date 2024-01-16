<?php

declare(strict_types=1);

namespace App\Modules\Price\Payment;

use App\Modules\Price\Enum\PaymentType;
use App\PaymentProcessor\StripePaymentProcessor;

readonly class StripeService implements PaymentTypeInterface
{
    public function __construct(
        protected readonly StripePaymentProcessor $stripePaymentProcessor,
    ) {
    }

    public function canHandle(string $paymentType): bool
    {
        return PaymentType::STRIPE->value === $paymentType;
    }

    public function payment(float $price): string
    {
        $res = $this->stripePaymentProcessor->processPayment($price);
        $message = "stripe complete $price, status - ";

        return $res ? $message . 'true' : $message . 'false';
    }
}
