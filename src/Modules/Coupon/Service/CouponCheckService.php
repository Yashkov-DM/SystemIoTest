<?php

declare(strict_types=1);

namespace App\Modules\Coupon\Service;

use App\Modules\Coupon\Repository\CouponRepositoryInterface;
use App\Modules\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Form\FormInterface;

class CouponCheckService implements CouponCheckServiceInterface
{
    public function __construct(
        private CouponRepositoryInterface $couponRepository,
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function getCouponCodeExist(FormInterface $form): array
    {
        $couponCode = $form->get('couponCode')->getViewData();

        $productId = $form->get('product')->getViewData();
        $product = $this->productRepository->findOneBy(['id' => $productId]);
        $price = $product?->getPrice();

        $regexp = "/[A-Z]+/";
        $match = [];
        $codeNumberCorrect = false;
        if(preg_match($regexp, $couponCode, $match)) {
            $code = $match[0];
            $codeNumber = str_replace($code, '', $couponCode);

            $coupon = $this->couponRepository->findOneBy(['code' => $code]);
            if($coupon) {
                $codeNumberCorrect = match ($coupon->getName()) {
                    'percent' => $codeNumber <= 100,
                    'fixed' => $price && $codeNumber <= $price,
                };
            }
        }

        return [
            'couponCodeCorrect' => !empty($coupon),
            'couponNumberCorrect' => $codeNumberCorrect
        ];
    }
}
