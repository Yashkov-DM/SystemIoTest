<?php

declare(strict_types=1);

namespace App\Modules\Price\UseCase;

use App\Form\Model\FormModel;
use App\Modules\Price\Form\PriceFormType;
use App\Modules\Price\Service\CalculatePriceServiceInterface;
use App\System\RequestInterface;
use App\Utils\Form\FormErrors;
use Symfony\Component\Form\FormFactoryInterface;
use App\Modules\Price\Response\CalculatePriceResponse;

readonly class GetPriceProductHandler implements GetPriceProductHandlerInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private CalculatePriceServiceInterface $calculatePriceService,
    ) {
    }

    public function handle(RequestInterface $request): CalculatePriceResponse
    {
        $data = $request->getData();

        $form = $this->formFactory->create(PriceFormType::class);
        $form->submit($data);

        $calculatePrice = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $priceProduct = $form->getData();
            $calculatePrice = $this->calculatePriceService->getCalculatePrice($priceProduct);
//            dd($form->isValid(), $calculatePrice);

        }
//        $errors = new FormErrors($form);
//        dd($form->isValid(), $errors, $data);

        return new CalculatePriceResponse(new FormModel($form), $calculatePrice);
    }
}
