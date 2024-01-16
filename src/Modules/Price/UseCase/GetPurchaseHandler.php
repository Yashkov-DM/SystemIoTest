<?php

declare(strict_types=1);

namespace App\Modules\Price\UseCase;

use App\Form\Model\FormModel;
use App\Modules\Price\Form\PurchaseFormType;
use App\Modules\Price\Service\CalculatePriceServiceInterface;
use App\System\RequestInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\Modules\Price\Response\PaymentPriceResponse;

readonly class GetPurchaseHandler implements GetPurchaseHandlerInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private CalculatePriceServiceInterface $calculatePriceService,
        private PaymentHandlerInterface $paymentHandler,
    ) {
    }

    public function handle(RequestInterface $request): PaymentPriceResponse
    {
        $data = $request->getData();

        $form = $this->formFactory->create(PurchaseFormType::class);
        $form->submit($data);

        $calculatePrice = '';
        $payment = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $priceProduct = $form->getData();
            $calculatePrice = $this->calculatePriceService->getCalculatePrice($priceProduct);

            $payment = $this->paymentHandler->handle($priceProduct->getPaymentProcessor(), (float) $calculatePrice);
        }

        return new PaymentPriceResponse(new FormModel($form), $calculatePrice, $payment);
    }
}
