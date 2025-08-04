<?php

namespace Webkul\Prestashop\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Prestashop\Helpers\PrestashopMetaFieldType;
use Webkul\Prestashop\Helpers\PrestashopFields;
use Webkul\Prestashop\Http\Requests\ExportMappingForm;
use Webkul\Prestashop\Repositories\PrestashopExportMappingRepository;

class MappingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected PrestashopExportMappingRepository $prestashopExportMappingRepository,
    ) {}

    /**
     * Display Shopify export mappings.
     */
    public function index(): View
    {
        $mappingFields = (new PrestashopFields)->getMappingField();
        $shopifyMapping = $this->prestashopExportMappingRepository->first();

        $object = (new PrestashopMetaFieldType);
        $metaFieldTypeInShopify = $object->getMetaFieldTypeInPrestashop();
        $attribute = [];
        $metafieldattrs = [];

        foreach ($shopifyMapping->mapping['shopify_connector_settings'] ?? [] as $row => $value) {
            $attribute[$row] = $value;
        }

        $formattedShopifyMapping = $attribute;
        $metafieldattr = [];

        foreach ($shopifyMapping->mapping['shopify_connector_others'] ?? [] as $row => $value) {
            $metafieldattrs[$row] = $value;
        }

        $formattedOtherMapping = $metafieldattrs;
        $shopifyDefaultMapping = [];

        foreach ($shopifyMapping->mapping['shopify_connector_defaults'] ?? [] as $row => $value) {
            $shopifyDefaultMapping[$row] = $value;
        }

        $mediaMapping = [];
        foreach ($shopifyMapping->mapping['mediaMapping'] ?? [] as $row => $value) {
            $mediaMapping[$row] = $value;
        }

        return view('prestashop::export.mapping.index', compact('mappingFields', 'formattedShopifyMapping', 'shopifyDefaultMapping', 'formattedOtherMapping', 'shopifyMapping', 'mediaMapping', 'metaFieldTypeInShopify'));
    }

    /**
     * Create or update Shopify export mapping.
     *
     * @param  FormRequest  $request
     */
    public function store(ExportMappingForm $request)
    {
        $data = $request->except(['_token', '_method']);
        $filteredData = array_filter($data);
        $mappingFields = [];

        $this->formatMediaMapping($filteredData, $mappingFields);

        $this->formatUnitMapping($filteredData, $mappingFields);

        foreach ($filteredData as $row => $value) {

            $sectionName = 'shopify_connector_settings';

            if (str_contains($row, 'default_')) {
                $sectionName = 'shopify_connector_defaults';
                $row = str_replace('default_', '', $row);
            }

            if (str_contains($row, 'meta_fields_')) {
                $sectionName = 'shopify_connector_others';
            }

            $mappingFields[$sectionName][$row] = $value;
        }

        $shopifyMapping = $this->prestashopExportMappingRepository->first();

        if ($shopifyMapping && $shopifyMapping->toArray()['mapping'] != $mappingFields) {
            $shopifyMapping = $this->prestashopExportMappingRepository->update(['mapping' => $mappingFields], 1);
        }

        session()->flash('success', trans('shopify::app.shopify.export.mapping.created'));

        return redirect()->route('admin.prestashop.export-mappings', 1);
    }

    public function formatMediaMapping(array &$filteredData, array &$mappingFields)
    {
        $type = 'mediaType';
        $attributes = 'mediaAttributes';
        $section = 'mediaMapping';

        if (isset($filteredData[$type]) && isset($filteredData[$attributes])) {
            $mappingFields[$section][$type] = $filteredData[$type];
            $mappingFields[$section][$attributes] = $filteredData[$attributes];

            unset($filteredData[$attributes]);
            unset($filteredData[$type]);
        }
    }

    public function formatUnitMapping(array &$filteredData, array &$mappingFields)
    {
        $mappingFields['unit']['weight'] = $filteredData['weightunit'] ?? null;
        $mappingFields['unit']['volume'] = $filteredData['volumeunit'] ?? null;
        $mappingFields['unit']['dimension'] = $filteredData['dimensionunit'] ?? null;
    }
}
