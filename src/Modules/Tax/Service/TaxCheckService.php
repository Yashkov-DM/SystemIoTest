<?php

declare(strict_types=1);

namespace App\Modules\Tax\Service;

use App\Modules\Tax\Repository\TaxRepositoryInterface;
use Symfony\Component\Form\FormInterface;

class TaxCheckService implements TaxCheckServiceInterface
{
    public function __construct(
        private TaxRepositoryInterface $taxRepository,
    )
    {
    }

    public function getTaxExist(FormInterface $form): bool
    {
        $taxCode = $form->get('taxNumber')->getViewData();

        $regexp = "/[A-Z]+/";
        $match = [];

        if(preg_match($regexp, $taxCode, $match)) {
            $countryCode = $match[0];
            $tax = $this->taxRepository->findOneBy(['code' => $countryCode]);
        }

        return isset($tax);
    }

    public function getTaxCorrect(FormInterface $form): bool
    {
        $taxCode = $form->get('taxNumber')->getViewData();

        $regexp = "/[A-Z]{2}/";

        $match = [];
        $correct = false;
        if(preg_match($regexp, $taxCode, $match)) {
            $countryCode = $match[0];
            $countryNumber = str_replace($countryCode, '', $taxCode);
            $tax = $this->taxRepository->findOneBy(['code' => $countryCode]);

            if($tax) {
                $countryFormat = $tax->getCodeNumber();
                $correct = strlen($countryFormat) == strlen($countryNumber);

                for($i = 0; $i < strlen($countryFormat); ++$i) {
                    if(!$correct) break;

                    $correct = match($countryFormat[$i]) {
                        'X' => preg_match("/[0-9]/", $countryNumber[$i]),
                        'Y' => preg_match("/[a-zA-Z]/", $countryNumber[$i])
                    };
                }
            }
        }

        return !empty($correct);
    }
}
