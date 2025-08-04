<?php

namespace Webkul\Prestashop\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Prestashop\DataGrids\Catalog\MetaFieldDataGrid;
use Webkul\Prestashop\Helpers\ShoifyMetaFieldType;
use Webkul\Prestashop\Http\Requests\MetaFieldForm;
use Webkul\Prestashop\Repositories\PrestashopMetaFieldRepository;

class MetaFieldController extends Controller
{
    public const SMALLESTUNIT = [
        'weight' => [
            'GRAMS'     => 1,
            'KILOGRAMS' => 1000,
            'POUNDS'    => 453.592,
            'OUNCES'    => 28.3495,
        ],

        'volume' => [
            'MILLILITERS'           => 1,
            'CENTILITERS'           => 10,
            'LITERS'                => 1000,
            'CUBIC_METERS'          => 1000000,
            'FLUID_OUNCES'          => 29.5735,
            'PINTS'                 => 473.176,
            'QUARTS'                => 946.353,
            'GALLONS'               => 3785.41,
            'IMPERIAL_FLUID_OUNCES' => 28.4131,
            'IMPERIAL_PINTS'        => 568.261,
            'IMPERIAL_QUARTS'       => 1136.52,
            'IMPERIAL_GALLONS'      => 4546.09,
        ],

        'dimension' => [
            'MILLIMETERS' => 1,
            'CENTIMETERS' => 10,
            'METERS'      => 1000,
            'INCHES'      => 25.4,
            'FEET'        => 304.8,
            'YARDS'       => 914.4,
        ],
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected PrestashopMetaFieldRepository $prestashopMetaFieldRepository,
        protected AttributeRepository $attributeRepository,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(MetaFieldDataGrid::class)->toJson();
        }

        $object = (new ShoifyMetaFieldType);
        $metaFieldType = $object->getMetaFieldType();
        $metaFieldTypeInShopify = $object->getMetaFieldTypeInShopify();

        return view('prestashop::metafield.index', compact('metaFieldType', 'metaFieldTypeInShopify'));
    }

    /**
     * Create a new MetaField.
     */
    public function store(MetaFieldForm $request): JsonResponse
    {
        $data = $request->all();
        $errors = [];
        if ((bool) $data['pin']) {
            $allPined = $this->prestashopMetaFieldRepository->where('pin', 1)->where('ownerType', $data['ownerType'])->get()->toArray();
            if (count($allPined) > 19) {
                $errors['pin'] = [trans('Pin limit reached, You can only have 20 pined fields')];
            }
        }

        $attributeCode = $this->prestashopMetaFieldRepository->where('code', $data['code'])->where('ownerType', $data['ownerType'])->get()->first();
        if ($attributeCode) {
            $defintionType = ($attributeCode?->ownerType == 'PRODUCT') ? 'Product Defintion' : 'Product variant Definition';
            $errors['code'] = [trans('Definition already created in '.$defintionType)];
        }
        if (isset($data['name_space_key'])) {
            $nameSpaceAndKeyExist = $this->prestashopMetaFieldRepository->where('name_space_key', $data['name_space_key'])
                ->where('ownerType', $data['ownerType'])->get()->first();
            if ($nameSpaceAndKeyExist) {
                $defintionType = ($nameSpaceAndKeyExist?->ownerType == 'PRODUCT') ? 'Product Defintion' : 'Product variant Definition';
                $errors['name_space_key'] = [trans('Namespace and key are already taken for '.$defintionType)];
            }
            $nameSpaceAndKey = explode('.', $data['name_space_key']);
            if (count($nameSpaceAndKey) > 2 || count($nameSpaceAndKey) < 2) {
                $errors['name_space_key'] = [trans('You need to use one period (.) to separate the namespace and key')];
            } elseif (strlen($nameSpaceAndKey[1]) < 2) {
                $errors['name_space_key'] = [trans('Key must be a minimum of 2 characters')];
            } elseif (strlen($nameSpaceAndKey[1]) > 64) {
                $errors['name_space_key'] = [trans('Key must be a maximum of 64 characters')];
            } elseif (! $this->isValidString($nameSpaceAndKey[1]) || ! $this->isValidString($nameSpaceAndKey[0])) {
                $errors['name_space_key'] = [trans('Namespace and key can only use letters, numbers, underscores, and dashes')];
            }
        }

        if (strlen($data['attribute']) > 255) {
            $errors['attribute'] = trans('Name is too long (maximum is 255 characters)');
        }

        if (empty($data['type'])) {
            $errors['type'] = [trans('Type Field is required')];
        }

        if (! empty($data['description']) && strlen($data['description']) > 100) {
            $errors['description'] = [trans('Description must be a maximum of 100 characters')];
        }

        $this->checkUnitValue($data, $errors);
        $validationValue = [];
        if (! empty($data['minvalue']) || ! empty($data['maxvalue'])) {
            $validationValue = [
                'max' => ! empty($data['maxvalue']) ? $data['maxvalue'] : null,
                'min' => ! empty($data['minvalue']) ? $data['minvalue'] : null,
            ];
        }

        if (! empty($data['maxunit']) || ! empty($data['minunit'])) {
            $validationValue['maxunit'] = ! empty($data['maxunit']) ? $data['maxunit'] : null;
            $validationValue['minunit'] = ! empty($data['minunit']) ? $data['minunit'] : null;
        }

        if (! empty($validationValue)) {
            $data['validations'] = json_encode($validationValue, true);
        }

        if (isset($data['adminFilterable']) || isset($data['smartCollectionCondition'])) {
            $data['options'] = json_encode([
                'adminFilterable'          => $data['adminFilterable'] ?? null,
                'smartCollectionCondition' => $data['smartCollectionCondition'] ?? null,
            ], true);
        }

        if (! empty($errors)) {
            return new JsonResponse([
                'errors' => $errors,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $metaFieldCreate = $this->prestashopMetaFieldRepository->create($data);

            session()->flash('success', trans('shopify::app.shopify.metafield.created'));
        } catch (\Exception $e) {
            return new JsonResponse([
                'errors' => [
                    'shopUrl'     => [$e->getMessage()],
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse([
            'redirect_url' => route('prestashop.metafield.edit', $metaFieldCreate->id),
        ]);
    }

    /**
     * Check Which is small by Uniit value and calculation
     */
    public function checkUnitValue($data, &$errors)
    {
        $maxvalue = null;
        $minvalue = null;
        $maxunit = null;
        $minunit = null;
        if (! empty($data['maxvalue'])) {
            if (isset($data['maxunit']) && empty($data['maxunit'])) {
                $errors['maxunit'] = ['required'];
            }
            $maxunit = $data['maxunit'] ?? null;

            $maxvalue = $data['maxvalue'];
        }

        if (! empty($data['minvalue'])) {
            if (isset($data['minunit']) && empty($data['minunit'])) {
                $errors['minunit'] = ['required'];
            }
            $minunit = $data['minunit'] ?? null;

            $minvalue = $data['minvalue'];
        }

        if ($minvalue && $maxvalue) {
            $unitData = self::SMALLESTUNIT[$data['type']] ?? null;
            if (! ctype_digit($minvalue)) {
                $errors['minvalue'] = [trans('Only Number Allowed')];

                return null;
            }
            if (! ctype_digit($maxvalue)) {
                $errors['maxvalue'] = [trans('Only Number Allowed')];

                return null;
            }
            if ($unitData) {
                $minvalue = $minvalue * ($unitData[$minunit] ?? 0);
                $maxvalue = $maxvalue * ($unitData[$maxunit] ?? 0);
            } else {
                $validateValue = function ($value, $type, $field) use (&$errors) {
                    if ($type !== 'date' && ! ctype_digit($value)) {
                        $errors[$field] = [trans('Only Number Allowed')];

                        return null;
                    }

                    return $type === 'date' ? new \DateTime($value) : (int) $value;
                };

                $minvalue = ! empty($data['minvalue'])
                    ? $validateValue($data['minvalue'], $data['type'] ?? '', 'minvalue')
                    : null;
                $maxvalue = ! empty($data['maxvalue'])
                    ? $validateValue($data['maxvalue'], $data['type'] ?? '', 'maxvalue')
                    : null;
            }

            if ($minvalue > $maxvalue) {
                $errorMsg = trans('Validations contains an invalid value: min must be less than max');
                $errors['minvalue'] = [$errorMsg];
                $errors['maxvalue'] = [$errorMsg];
            }
        }

        if ($data['type'] == 'rating') {
            if (! $minvalue || ! $maxvalue) {
                $errors['minvalue'] = $errors['maxvalue'] = [trans('Rating field must have both min and max values')];
            }
        }
    }

    /**
     * Check if namespace and key valid strings or not.
     *
     * @return View
     */
    public function isValidString($string)
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $string);
    }

    /**
     * Edit a MetaField Definition by ID.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $metaField = $this->prestashopMetaFieldRepository->find($id);

        if (! $metaField) {
            abort(404);
        }

        $object = (new ShoifyMetaFieldType);
        $metaFieldType = $object->getMetaFieldType();
        $metaFieldTypeInShopify = $object->getMetaFieldTypeInShopify();

        return view('prestashop::metafield.edit', compact('metaField', 'metaFieldType', 'metaFieldTypeInShopify'));
    }

    /**
     * Update a Meta Field by ID.
     *
     * @return JsonResponse
     */
    public function update(int $id)
    {
        $requestData = request()->except(['_token', '_method', 'listvalue']);
        $errors = [];
        if ((bool) $requestData['pin']) {
            $allPined = $this->prestashopMetaFieldRepository->where('pin', 1)->where('ownerType', $requestData['ownerType'])->get()->toArray();
            $attrCode = array_column($allPined, 'code');
            $countPin = count($allPined);
            if (in_array($requestData['code'], $attrCode)) {
                $filtered = array_filter($allPined, fn ($item) => $item['code'] === $requestData['code']);
                $oneField = reset($filtered);
                if ((bool) $oneField['pin']) {
                    $countPin = $countPin - 1;
                }
            }

            if ($countPin > 19) {
                $errors['pin'] = [trans('Pin limit reached, You can only have 20 pined fields')];
            }
        }

        $this->checkUnitValue($requestData, $errors);
        $validationValue = [];
        $requestData['validations'] = null;
        if (! empty($requestData['minvalue']) || ! empty($requestData['maxvalue'])) {
            $validationValue = [
                'max' => ! empty($requestData['maxvalue']) ? $requestData['maxvalue'] : null,
                'min' => ! empty($requestData['minvalue']) ? $requestData['minvalue'] : null,
            ];
            if (! empty($requestData['maxunit']) || ! empty($requestData['minunit'])) {
                $validationValue['maxunit'] = ! empty($requestData['maxunit']) ? $requestData['maxunit'] : null;
                $validationValue['minunit'] = ! empty($requestData['minunit']) ? $requestData['minunit'] : null;
            }
        }

        if (! empty($validationValue)) {
            $formattedValue = json_encode($validationValue, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $formattedValue = preg_replace('/:/', ': ', $formattedValue);
            $formattedValue = preg_replace('/,/', ', ', $formattedValue);
            $requestData['validations'] = $formattedValue;
        }
        // Prepare options if they exist.
        if (isset($requestData['adminFilterable']) || isset($requestData['smartCollectionCondition'])) {
            // Encode with pretty formatting
            $formatted = json_encode([
                'adminFilterable'          => $requestData['adminFilterable'] ?? null,
                'smartCollectionCondition' => $requestData['smartCollectionCondition'] ?? null,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $formatted = preg_replace('/:/', ': ', $formatted);
            $formatted = preg_replace('/,/', ', ', $formatted);
            $requestData['options'] = $formatted;
        }

        // Additional validations for description and attribute length.
        if (isset($requestData['description']) && strlen($requestData['description']) > 100) {
            $errors['description'] = trans('Description must be a maximum of 100 characters');
        }

        if (isset($requestData['attribute']) && strlen($requestData['attribute']) > 255) {
            $errors['attribute'] = trans('Name is too long (maximum is 255 characters)');
        }

        $credential = $this->prestashopMetaFieldRepository->find($id);
        if (! $credential) {
            abort(404);
        }

        if (! empty($errors)) {
            return redirect()->route('prestashop.metafield.edit', $id)
                ->withErrors($errors)
                ->withInput();
        }
        // Proceed to update after validation passes.
        $this->prestashopMetaFieldRepository->update($requestData, $id);
        session()->flash('success', trans('shopify::app.shopify.metafield.update-success'));

        return redirect()->route('prestashop.metafield.edit', $id);
    }

    /**
     * Delete a MetaField ID.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->prestashopMetaFieldRepository->delete($id);

        return new JsonResponse([
            'message' => trans('shopify::app.shopify.metafield.delete-success'),
        ]);
    }

    /**
     * Mass Destroy deletes a MetaField
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $metaFieldsId = $massDestroyRequest->input('indices');

        if (empty($metaFieldsId)) {
            return new JsonResponse([
                'message' => trans('shopify::app.shopify.metafield.no-selected'),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $deletedMetaField = $this->prestashopMetaFieldRepository->whereIN('id', $metaFieldsId)->delete();
            if ($deletedMetaField) {
                $message = trans('shopify::app.shopify.metafield.mass-delete-success');
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse([
            'message' => $message,
        ]);
    }
}
