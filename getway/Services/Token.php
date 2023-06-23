<?php

namespace App\Services\Token;

use App\Traits\RequestService;
use GuzzleHttp\Exception\GuzzleException;

class Token
{
    use RequestService;

    protected readonly string $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.user_management.base_uri');
    }

    /**
     * @param array $payload
     *
     * @return string
     * @throws GuzzleException
     **/
    public function listToken(
        array $payload
    ): string
    {
        return $this->request('GET', 'list-token', $payload);
    }

    public function addToken(
        array $payload
    ): string
    {
        return $this->request('POST', 'add-token', $payload);
    }

    public function delToken(
        array $payload
    ): string
    {
        return $this->request('POST', 'del-token', $payload);
    }

}
