<?php

declare(strict_types=1);

namespace App\Utils\Form;

use Symfony\Component\Form\FormInterface;

interface FormErrorFactoryInterface
{
    public function create(FormInterface $form): FormErrors;
}
