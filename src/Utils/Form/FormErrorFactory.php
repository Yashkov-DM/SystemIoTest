<?php

declare(strict_types=1);

namespace App\Utils\Form;

use Symfony\Component\Form\FormInterface;

class FormErrorFactory implements FormErrorFactoryInterface
{
    public function create(FormInterface $form): FormErrors
    {
        return new FormErrors($form);
    }
}
