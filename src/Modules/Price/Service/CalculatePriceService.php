<?php

declare(strict_types=1);

namespace App\Modules\Price\Service;

use App\Modules\Coupon\Repository\CouponRepositoryInterface;
use App\Modules\Price\Entity\PriceProduct;
use App\Modules\Tax\Repository\TaxRepositoryInterface;

class CalculatePriceService implements CalculatePriceServiceInterface
{
    public function __construct(
        private TaxRepositoryInterface $taxRepository,
        private CouponRepositoryInterface $couponRepository
    )
    {
    }

    public function getCalculatePrice(PriceProduct $priceProduct): string
    {
        $productPrice = $priceProduct->getProduct()->getPrice();

        $taxCode = $priceProduct->getTaxNumber();

        $regexp = "/[A-Z]{2}/";
        $match = [];
        preg_match($regexp, $taxCode, $match);

        $tax = $this->taxRepository->findOneBy(['code' => $match[0]]);
        $taxPercent = $tax->getPercent();

        $productPriceFinal = $productPrice + $productPrice * $taxPercent/100;

        $couponCode = $priceProduct->getCouponCode();
        if($couponCode) {

            $regexp = "/[A-Z]+/";
            $match = [];
            preg_match($regexp, $couponCode, $match);

            $codeNumber = str_replace($match[0], '', $couponCode);
            $coupon = $this->couponRepository->findOneBy(['code' => $match[0]]);

            $productPriceFinal = match ($coupon->getName()) {
                'percent' => $productPriceFinal - $productPriceFinal * $codeNumber/100,
                'fixed' => $productPriceFinal - $codeNumber,
            };

        }

        return (string) $productPriceFinal;
    }
}
