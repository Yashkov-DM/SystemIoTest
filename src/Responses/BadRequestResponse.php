<?php

declare(strict_types=1);

namespace App\Responses;

use App\Traits\ResultTrait;
use OpenApi\Attributes as SWG;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Serializer\Annotation\Groups;

#[SWG\Schema(title: 'Bad request response', required: ['result', 'message'], type: 'object')]
class BadRequestResponse extends Response
{
    use ResultTrait;
    final public const DEFAULT_MESSAGE = 'Bad request';

    #[SWG\Property(description: 'Bad request message', type: 'string')]
    #[Groups(['swagger'])]
    public string $message = self::DEFAULT_MESSAGE;

    public function __construct(string $message = null)
    {
        if (null !== $message) {
            $this->message = $message;
        }
    }

    public function getResponseCode(): int
    {
        return SymfonyResponse::HTTP_BAD_REQUEST;
    }
}
