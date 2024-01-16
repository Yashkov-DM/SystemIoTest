<?php

declare(strict_types=1);

namespace App\System;

use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class Request implements RequestInterface
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getBaseRequest(): BaseRequest
    {
        return $this->requestStack->getCurrentRequest() ?? BaseRequest::createFromGlobals();
    }

    public function getMethod(): string
    {
        return $this->getBaseRequest()->getMethod();
    }

    public function isMethod(string $method): bool
    {
        return $this->getBaseRequest()->isMethod($method);
    }

    public function getClientIp(): string
    {
        $request = $this->getBaseRequest();

        return
            $request->server->get('HTTP_X_FORWARDED_FOR') ??
            $request->server->get('HTTP_X_REAL_IP') ??
            $request->server->get('HTTP_CLIENT_IP') ??
            $request->server->get('REMOTE_ADDR');
    }

    /**
     * @throws \JsonException
     */
    public function getData(): array
    {
        $request = $this->getBaseRequest();
        $data = $request->query->all() ?: $request->request->all();

        if (!$data) {
            $data = \json_decode(
                $request->getContent(),
                true,
                512,
                JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR
            );

            if (JSON_ERROR_NONE !== \json_last_error()) {
                $data = [];
            }
        }

        return $data;
    }
}
