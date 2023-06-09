<?php

namespace Ls\ClientAssistant\Utilities\Modules;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Collection;
use Ls\ClientAssistant\Core\Contracts\ModuleUtility;
use Ls\ClientAssistant\Core\Enums\OrderByEnum;
use Ls\ClientAssistant\Core\GuzzleClient;

class Enrollment extends ModuleUtility
{
    public static function get(string $id, array $with = []): Collection
    {
        return GuzzleClient::get('v1/lms/enrollment/' . $id, [
            'with' => json_encode($with),
        ]);
    }

    public static function list(array $with = [], array $keyValues = [], int $perPage = 20, $orderBy = OrderByEnum::LATEST): Collection
    {
        return GuzzleClient::get('v1/lms/enrollment', [
            'with' => json_encode($with),
            'filter' => json_encode($keyValues),
            'order_by' => $orderBy,
            'per_page' => $perPage,
        ]);
    }

    public static function search(string $keyword, array $columns = [], array $with = [], int $perPage = 20): Collection
    {
        return GuzzleClient::get('v1/lms/enrollment', [
            's' => $keyword,
            'with' => json_encode($with),
            'columns' => json_encode($columns),
            'per_page' => $perPage,
        ]);
    }

    public static function signal(int $enrollmentId, int $productItem, string $userToken, string $type): Collection
    {
        if (!in_array($type, ['visited', 'completed', 'played'])) {
            throw new \InvalidArgumentException('Type must be in [visited, completed, played]');
        }

        $response = GuzzleClient::put(('v1/lms/enrollment/' . $enrollmentId . '/signal/' . $productItem), [
            'signal' => $type,
        ], ['Authorization' => 'Bearer ' . $userToken]);

        return GuzzleClient::parseData($response);
    }

    public static function logs(int $enrollmentId, string $userToken): Collection
    {
        $guzzle = GuzzleClient::self();
        $response = $guzzle->get(('v1/lms/enrollment/' . $enrollmentId . '/logs'), [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $userToken,
            ]
        ]);

        return collect(json_decode($response->getBody()->getContents()));
    }
}