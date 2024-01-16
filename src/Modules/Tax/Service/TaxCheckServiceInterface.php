<?php

declare(strict_types=1);

namespace App\Modules\Tax\Service;

use Symfony\Component\Form\FormInterface;

interface TaxCheckServiceInterface
{
    public function getTaxExist(FormInterface $form): bool;
    public function getTaxCorrect(FormInterface $form): bool;
}
