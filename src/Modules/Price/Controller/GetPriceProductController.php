<?php

declare(strict_types=1);

namespace App\Modules\Price\Controller;

use App\Modules\Price\Response\CalculatePriceResponse;
use App\Modules\Price\UseCase\GetPriceProductHandlerInterface;
use App\Responses\NotFoundResponse;
use App\System\RequestInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[SWG\Tag(name: 'Payments')]
class GetPriceProductController extends AbstractController
{
    public function __construct(private readonly GetPriceProductHandlerInterface $handler)
    {
    }

    #[Route('/api/v1/calculate/price', name: 'api.get.calculate.price', methods: ['POST'])]
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
                    ],
                )
            ),
        ],
    )]
    #[SWG\Response(
        response: 200,
        description: 'Price product calculate',
        content: new SWG\JsonContent(ref: new Model(type: CalculatePriceResponse::class, groups: ['swagger']))
    )]
    #[SWG\Response(
        response: 400,
        description: 'Not found response',
        content: new SWG\JsonContent(ref: new Model(type: NotFoundResponse::class, groups: ['swagger']))
    )]
    public function __invoke(RequestInterface $request): CalculatePriceResponse
    {
        return $this->handler->handle($request);
    }
}
