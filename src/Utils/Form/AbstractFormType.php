<?php

declare(strict_types=1);

namespace App\Utils\Form;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class AbstractFormType extends AbstractType implements ServiceSubscriberInterface
{
    private ?FormErrors $formErrors = null;

    public function __construct(protected ContainerInterface $container)
    {
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getFormErrors(FormInterface $form): FormErrors
    {
        $this->formErrors = $this->formErrors ?? $this->getFormErrorFactory()->create($form);

        return $this->formErrors;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getFormErrorFactory(): FormErrorFactoryInterface
    {
        return $this->container->get(FormErrorFactoryInterface::class);
    }

    public static function getSubscribedServices(): array
    {
        return [
            FormErrorFactoryInterface::class,
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'csrf_protection' => false,
                    'allow_extra_fields' => true,
                    'csrf_field_name' => 'csrf_token',
                ]
            )
        ;
    }
}
