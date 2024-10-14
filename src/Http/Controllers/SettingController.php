<?php

namespace Webkul\Shopify\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Shopify\Repositories\ShopifyExportMappingRepository;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ShopifyExportMappingRepository $shopifyExportMappingRepository,
    ) {}

    /**
     * Display Shopify settings.
     */
    public function index(int $id): View
    {
        $shopifySettings = $this->shopifyExportMappingRepository->find(2);

        return view('shopify::export.settings.index', compact('shopifySettings'));
    }

    /**
     * Create or update Shopify export settings.
     */
    public function store(FormRequest $request)
    {
        $data = $request->except(['_token', '_method']);

        $filteredData = array_filter($data);

        $shopifySettings = $this->shopifyExportMappingRepository->find(2);

        if ($shopifySettings) {
            if (isset($filteredData['enable_named_tags_attribute']) || ! isset($filteredData['enable_tags_attribute'])) {
                unset($filteredData['tagSeprator']);
            }

            $filteredDataforSettings['mapping'] = $filteredData;

            if ($shopifySettings->mapping != $filteredDataforSettings['mapping']) {
                $shopifyMapping = $this->shopifyExportMappingRepository->update($filteredDataforSettings, 2);
            }
        }

        session()->flash('success', trans('shopify::app.shopify.export.settings.created'));

        return redirect()->route('admin.shopify.settings', 2);
    }
}
