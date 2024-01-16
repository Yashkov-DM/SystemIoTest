<?php

declare(strict_types=1);

namespace App\System;

use Symfony\Component\HttpFoundation\Request;

interface RequestInterface
{
    public function getBaseRequest(): Request;

    public function getMethod(): string;

    public function isMethod(string $method): bool;

    public function getClientIp(): string;

    public function getData(): array;
}
