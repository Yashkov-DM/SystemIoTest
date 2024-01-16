<?php

declare(strict_types=1);

namespace App\Modules\Price\Form;

use App\Modules\Coupon\Service\CouponCheckServiceInterface;
use App\Modules\Price\Entity\PriceProduct;
use App\Modules\Product\Form\ProductTransformer;
use App\Modules\Tax\Service\TaxCheckServiceInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class PurchaseFormType extends AbstractType implements ServiceSubscriberInterface
{
    public function __construct(
        private readonly ProductTransformer $productTransformer,
        protected ContainerInterface $container
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Значение поля «taxNumber» не должно быть пустым.']),
                ],
            ])
            ->add('taxNumber', TextType::class, [
                'constraints' => [
                    new Callback(fn (...$args) => $this->validateTax(...$args)),
                    new NotBlank(['message' => 'Значение поля «taxNumber» не должно быть пустым.']),
                ],
            ])
            ->add('couponCode', TextType::class, [
                'constraints' => [
                    new Callback(fn (...$args) => $this->validateCouponCode(...$args)),
                ],
            ])
            ->add('paymentProcessor', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Значение поля «paymentProcessor» не должно быть пустым.']),
                ],
            ])
        ;

        $builder->get('product')
            ->addModelTransformer($this->productTransformer);
    }

    private function validateCouponCode($couponCode, ExecutionContextInterface $context): void
    {
        if ($couponCode) {
            $checkList = $this->getCouponCheckService()->getCouponCodeExist($context->getRoot());

            if (!$checkList['couponCodeCorrect']) {
                $context->addViolation('Значение поля «couponCode» не соответствует кодировке.');
            }

            if (!$checkList['couponNumberCorrect']) {
                $context->addViolation('Не верно указан номинал скидки в поле «couponCode».');
            }
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function validateTax($tax, ExecutionContextInterface $context): void
    {
        if ($tax) {

            $checkCountryCod = $this->getTaxCheckService()->getTaxExist($context->getRoot());
            if(!$checkCountryCod) {
                $context->addViolation('Не верно указан код страны в поле «taxNumber».');
            }

            $checkCountryNumber = $this->getTaxCheckService()->getTaxCorrect($context->getRoot());
            if(!$checkCountryNumber) {
                $context->addViolation('Формат налогового номера в поле «taxNumber» не соответствует коду страны.');
            }
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getCouponCheckService(): CouponCheckServiceInterface
    {
        return $this->container->get(CouponCheckServiceInterface::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getTaxCheckService(): TaxCheckServiceInterface
    {
        return $this->container->get(TaxCheckServiceInterface::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PriceProduct::class,
            'csrf_protection' => false,
            'allow_extra_fields' => false,
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return [
            CouponCheckServiceInterface::class,
            TaxCheckServiceInterface::class,
        ];
    }
}
