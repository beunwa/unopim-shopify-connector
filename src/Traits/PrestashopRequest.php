<?php

namespace Webkul\Prestashop\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Webkul\DataTransfer\Helpers\Export as ExportHelper;
use Webkul\DataTransfer\Models\JobTrack;
use Webkul\Prestashop\Exceptions\InvalidCredential;
use Webkul\Prestashop\Http\Client\PrestashopApiClient;

trait PrestashopRequest
{
    protected function requestPrestashopApiAction(
        string $endpoint,
        ?array $credential = [],
        ?array $parameters = [],
        string $method = 'GET',
        ?array $payload = []
    ): array {
        if (! $credential || ! isset($credential['shopUrl']) || ! isset($credential['apiKey'])) {
            throw new \InvalidArgumentException('Invalid Prestashop credentials provided.');
        }

        $apiKey = $credential['apiKey'];

        $client = new PrestashopApiClient($credential['shopUrl'], $apiKey);

        $response = $client->request($endpoint, $parameters ?? [], $payload ?? [], $method);

        if (
            (! $response['code'] || in_array($response['code'], [401, 404]))
            && property_exists($this, 'export')
            && $this->export instanceof JobTrack
        ) {
            $this->export->state = ExportHelper::STATE_FAILED;
            $this->export->errors = [trans('shopify::app.shopify.export.errors.invalid-credential')];
            $this->export->save();

            throw new InvalidCredential;
        }

        return $response;
    }

    public function handleUrlField(mixed $imageUrl, string $imagePath): string|bool
    {
        try {
            $response = Http::get($imageUrl);

            if ($response->failed()) {
                return false;
            }

            $imageContents = $response->body();

            $path = parse_url($imageUrl, PHP_URL_PATH);

            $fileName = basename($path);

            if (! preg_match('/\.[a-zA-Z0-9]+$/', $fileName)) {
                $fileName .= '.png';
            }

            $path = $imagePath.$fileName;

            StorageFacade::disk('public')->put($path, $imageContents);

            return $path;
        } catch (\Exception $e) {
            return false;
        }
    }
}
