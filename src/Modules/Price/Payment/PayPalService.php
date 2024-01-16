<?php

declare(strict_types=1);

namespace App\Modules\Price\Payment;

use App\Modules\Price\Enum\PaymentType;
use App\PaymentProcessor\PaypalPaymentProcessor;

readonly class PayPalService implements PaymentTypeInterface
{
    public function __construct(
        protected readonly PaypalPaymentProcessor $paypalPaymentProcessor,
    ) {
    }

    public function canHandle(string $paymentType): bool
    {
        return PaymentType::PAYPAL->value === $paymentType;
    }

    /**
     * @throws \Exception
     */
    public function payment(float $price): string
    {
        $this->paypalPaymentProcessor->pay((int) $price);

        return "paypal complete $price";
    }
}
