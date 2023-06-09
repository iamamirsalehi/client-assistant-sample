<?php

namespace Ls\ClientAssistant\Helpers;

use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Response
{
    public static function single(ResponseInterface $response): Collection
    {
        if (in_array($response->getStatusCode(), [200, 201])) {
            return collect(json_decode($response->getBody(), true));
        }

        return collect();
    }

    public static function many(ResponseInterface $response): Collection
    {
        if (in_array($response->getStatusCode(), [200, 201])) {
            return collect(json_decode($response->getBody(), true));
        }

        return collect();
    }
}