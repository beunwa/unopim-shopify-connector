<?php

namespace Webkul\Prestashop\Http\Client;

use Illuminate\Support\Facades\Http;

class PrestashopApiClient
{
    protected string $url;

    protected string $apiKey;

    protected array $options;

    public function __construct(string $url, string $apiKey, array $options = [])
    {
        $this->url = rtrim(str_replace(['http://'], ['https://'], $url), '/').'/api';
        $this->apiKey = $apiKey;
        $this->options = $options;
    }

    protected function buildApiUrl(string $endpoint): string
    {
        return $this->url.'/'.ltrim($endpoint, '/');
    }

    protected function getRequestHeaders(): array
    {
        return [
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic '.base64_encode($this->apiKey.':'),
        ];
    }

    public function request(string $endpoint, array $parameters = [], array $payload = [], string $method = 'GET'): array
    {
        $url = $this->buildApiUrl($endpoint);

        $options = [];

        if ($method === 'GET') {
            if (! empty($parameters)) {
                $options['query'] = $parameters;
            }
        } else {
            if (! empty($parameters)) {
                $url .= '?'.http_build_query($parameters);
            }

            if (! empty($payload)) {
                $options['json'] = $payload;
            }
        }

        try {
            $response = Http::withHeaders($this->getRequestHeaders())
                ->timeout($this->options['timeout'] ?? 120)
                ->retry(3, 100)
                ->send($method, $url, $options);

            return [
                'code' => $response->status(),
                'body' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ];
        }
    }
}
