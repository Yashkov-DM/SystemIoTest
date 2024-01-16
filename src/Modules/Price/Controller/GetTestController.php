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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[SWG\Tag(name: 'Payments')]
class GetTestController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/api/v1/test', name: 'api.get.test', methods: ['GET'])]
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
    public function __invoke(RequestInterface $request): JsonResponse
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
