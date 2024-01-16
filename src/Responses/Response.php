<?php

declare(strict_types=1);

namespace App\Responses;

use App\Traits\ResultTrait;
use OpenApi\Attributes as SWG;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Serializer\Annotation\Groups;

abstract class Response implements ResponseInterface
{
    use ResultTrait;

    #[SWG\Property(
        property: 'data',
        description: 'Response data',
        type: 'object'
    )]
    #[Groups(['swagger'])]
    public array $data = [];

    #[SWG\Property(
        property: 'errors',
        description: 'Response errors',
        type: 'object',
    )]
    #[Groups(['swagger'])]
    public array $errors = [];

    public function getResponseCode(): int
    {
        return SymfonyResponse::HTTP_OK;
    }

    public function getResponseData(): array
    {
        return [
            'result' => [] === $this->getErrors(),
            'data' => (object) $this->getData(),
            'errors' => $this->getErrors(),
        ];
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function jsonSerialize(): array
    {
        return $this->getResponseData();
    }
}
