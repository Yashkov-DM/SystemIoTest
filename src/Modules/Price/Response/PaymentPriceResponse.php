<?php

declare(strict_types=1);

namespace App\Modules\Price\Response;

use App\Form\Model\FormModel;
use App\Responses\Response;
use App\Traits\ResultTrait;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

#[SWG\Schema(title: 'Payment price response', required: ['result', 'data', 'errors'], type: 'object')]
class PaymentPriceResponse extends Response
{
    use ResultTrait;

    #[SWG\Property(
        property: 'data',
        description: 'Response data',
        properties: [
            new SWG\Property(property: 'price', type: 'string', example: '120'),
            new SWG\Property(property: 'payment', type: 'string', example: 'paypal complete'),
        ],
        type: 'object'
    )]
    #[Groups(['swagger'])]
    public array $data = [];


    #[SWG\Property(
        property: 'errors',
        description: 'Response errors',
        properties: [
            new SWG\Property(
                property: 'error',
                type: 'array',
                items: new SWG\Items(
                    type: 'string',
                    example: 'Значение поля «taxNumber» не должно быть пустым.'
                )
            ),
        ],
        type: 'object'
    )]
    #[Groups(['swagger'])]
    public array $errors = [];

    public function __construct(FormModel $form, string $calculatePrice = null, mixed $payment = null)
    {
        $this->data = [
            'price' => $calculatePrice,
            'payment' => $payment,
            ];
        $this->errors = $form->errors->getErrors();
    }
}
