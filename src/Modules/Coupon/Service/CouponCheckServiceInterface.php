<?php

declare(strict_types=1);

namespace App\Modules\Coupon\Service;

use Symfony\Component\Form\FormInterface;

interface CouponCheckServiceInterface
{
    public function getCouponCodeExist(FormInterface $form): array;
}
