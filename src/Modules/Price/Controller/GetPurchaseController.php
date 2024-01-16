<?php

declare(strict_types=1);

namespace App\Modules\Price\Controller;

use App\Modules\Price\Response\PaymentPriceResponse;
use App\Modules\Price\UseCase\GetPurchaseHandlerInterface;
use App\Responses\NotFoundResponse;
use App\System\RequestInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[SWG\Tag(name: 'Payments')]
class GetPurchaseController extends AbstractController
{
    public function __construct(private readonly GetPurchaseHandlerInterface $handler)
    {
    }

    #[Route('/api/v1/purchase', name: 'api.get.purchase', methods: ['POST'])]
    #[SWG\RequestBody(
        description : '',
        content : [
            new SWG\MediaType(
                mediaType : 'application/json',
                schema: new SWG\Schema(
                    properties: [
                        new SWG\Property(property: 'product', type: 'integer', example: 1),
                        new SWG\Property(property: 'taxNumber', type: 'string', example: 'DE123456789'),
                        new SWG\Property(property: 'couponCode', type: 'string', example: 'D15'),
                        new SWG\Property(property: 'paymentProcessor', type: 'string', example: 'paypal'),
                    ],
                )
            ),
        ],
    )]
    #[SWG\Response(
        response: 200,
        description: 'Price product calculate',
        content: new SWG\JsonContent(ref: new Model(type: PaymentPriceResponse::class, groups: ['swagger']))
    )]
    #[SWG\Response(
        response: 400,
        description: 'Not found response',
        content: new SWG\JsonContent(ref: new Model(type: NotFoundResponse::class, groups: ['swagger']))
    )]
    public function __invoke(RequestInterface $request): PaymentPriceResponse
    {
        return $this->handler->handle($request);
    }
}
