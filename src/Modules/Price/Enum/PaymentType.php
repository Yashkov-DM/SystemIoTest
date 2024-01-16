<?php

declare(strict_types=1);

namespace App\Modules\Price\Enum;

enum PaymentType: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';
}
