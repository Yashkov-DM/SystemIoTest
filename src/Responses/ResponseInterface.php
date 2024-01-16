<?php

declare(strict_types=1);

namespace App\Responses;

interface ResponseInterface extends \JsonSerializable
{
    public function getResponseCode(): int;

    public function getResponseData(): array;
}
