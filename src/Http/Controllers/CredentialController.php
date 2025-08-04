<?php

namespace Webkul\Prestashop\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Prestashop\DataGrids\Catalog\CredentialDataGrid;
use Webkul\Prestashop\Helpers\ShoifyApiVersion;
use Webkul\Prestashop\Http\Requests\CredentialForm;
use Webkul\Prestashop\Repositories\PrestashopCredentialRepository;
use Webkul\Prestashop\Traits\PrestashopRequest;

class CredentialController extends Controller
{
    use PrestashopRequest;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PrestashopCredentialRepository $prestashopRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CredentialDataGrid::class)->toJson();
        }

        $apiVersion = (new ShoifyApiVersion)->getApiVersion();

        return view('prestashop::credential.index', compact('apiVersion'));
    }

    /**
     * Create a new Shopify credential.
     */
    public function store(CredentialForm $request): JsonResponse
    {
        $data = $request->all();
        $url = $data['shopUrl'];
        $url = $data['shopUrl'] = rtrim($url, '/');

        if (strpos($url, 'http') !== 0) {
            return new JsonResponse([
                'errors' => [
                    'shopUrl' => [trans('shopify::app.shopify.credential.invalidurl')],
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credential = $this->prestashopRepository->findWhere(['shopUrl' => $url])->first();

        if ($credential) {
            return new JsonResponse([
                'errors' => [
                    'shopUrl' => [trans('shopify::app.shopify.credential.already_taken')],
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data['active'] = 1;

        $response = $this->requestPrestashopApiAction('products', $data);

        if ($response['code'] != JsonResponse::HTTP_OK) {
            return new JsonResponse([
                'errors' => [
                    'shopUrl'     => [trans('shopify::app.shopify.credential.invalid')],
                    'apiKey' => [trans('shopify::app.shopify.credential.invalid')],
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $credentialCreate = $this->prestashopRepository->create($data);

            session()->flash('success', trans('shopify::app.shopify.credential.created'));
        } catch (\Exception $e) {
            return new JsonResponse([
                'errors' => [
                    'shopUrl'     => [$e->getMessage()],
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse([
            'redirect_url' => route('prestashop.credentials.edit', $credentialCreate->id),
        ]);
    }

    /**
     * Delete a Shopify credential by ID.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->prestashopRepository->delete($id);

        return new JsonResponse([
            'message' => trans('shopify::app.shopify.credential.delete-success'),
        ]);
    }

    /**
     * Edit a Shopify credential by ID.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $credential = $this->prestashopRepository->find($id);

        if (! $credential) {
            abort(404);
        }

        $credentialData = $credential->getAttributes();

        $response = $this->requestPrestashopApiAction('shop/locales', $credentialData);

        $publishing = $this->requestPrestashopApiAction('publications', $credentialData);

        $locationGetting = $this->requestPrestashopApiAction('locations', $credentialData);

        $locationAll = $locationGetting['body']['data']['locations']['edges'] ?? [];

        $publishingChannel = $publishing['body']['data']['publications']['edges'] ?? [];

        $shopLocales = $response['body']['data']['shopLocales'] ?? [];

        $apiVersion = (new ShoifyApiVersion)->getApiVersion();

        $credential->apiKey = str_repeat('*', strlen($credential->apiKey));

        return view('prestashop::credential.edit', compact('credential', 'shopLocales', 'publishingChannel', 'locationAll', 'apiVersion'));
    }

    /**
     * Update a Shopify credential by ID.
     *
     * @return JsonResponse
     */
    public function update(int $id)
    {
        $requestData = request()->except(['code']);
        $credential = $this->prestashopRepository->find($id);

        if (! $credential) {
            abort(404);
        }

        $token = str_repeat('*', strlen($credential->apiKey));

        if (str_contains($requestData['apiKey'], $token)) {
            $requestData['apiKey'] = $credential->apiKey;
        }

        $params = $this->validate(request(), [
            'shopUrl'     => 'required|url',
            'apiKey' => 'required',
        ]);

        $response = $this->requestPrestashopApiAction('products', $requestData);

        if ($response['code'] != 200) {
            return redirect()->route('prestashop.credentials.edit', $id)
                ->withErrors([
                    'shopUrl'     => trans('shopify::app.shopify.credential.invalid'),
                    'apiKey' => trans('shopify::app.shopify.credential.invalid'),
                ])
                ->withInput();
        }

        $keyOrder = ['name', 'locale', 'primary', 'published'];

        $languages = json_decode($requestData['storeLocales'], true);

        $languages = array_map(function ($item) use ($keyOrder) {
            return array_merge(array_flip($keyOrder), $item);
        }, $languages);

        $languages = array_map(function ($language) {
            if ($language['primary']) {
                $language['defaultlocale'] = true;
            }

            return $language;
        }, $languages);

        $requestData['storeLocales'] = $languages;

        $extras = $credential->extras;

        $extras['locations'] = $requestData['locations'];

        $extras['salesChannel'] = $requestData['salesChannel'];

        $requestData['extras'] = $extras;

        unset($requestData['salesChannel']);
        unset($requestData['locations']);

        $this->prestashopRepository->update($requestData, $id);

        session()->flash('success', trans('shopify::app.shopify.credential.update-success'));

        return redirect()->route('prestashop.credentials.edit', $id);
    }
}
