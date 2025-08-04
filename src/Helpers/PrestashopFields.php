<?php

namespace Webkul\Prestashop\Helpers;

class PrestashopFields
{
    /**
     * PrestaShop Mapping Fields.
     *
     * @var array
     */
    public array $mappingFields = [
        [
            'name'  => 'name',
            'label' => 'prestashop::app.prestashop.fields.name',
            'types' => ['text'],
            'tooltip' => 'supported attributes types: text',
        ],
        [
            'name'  => 'description',
            'label' => 'prestashop::app.prestashop.fields.description',
            'types' => ['textarea'],
            'tooltip' => 'supported attributes types: textarea',
        ],
        [
            'name'  => 'price',
            'label' => 'prestashop::app.prestashop.fields.price',
            'types' => ['price'],
            'tooltip' => 'supported attributes types: price',
        ],
        [
            'name'  => 'weight',
            'label' => 'prestashop::app.prestashop.fields.weight',
            'types' => ['number', 'decimal'],
            'tooltip' => 'supported attributes types: number, decimal',
        ],
        [
            'name'  => 'quantity',
            'label' => 'prestashop::app.prestashop.fields.quantity',
            'types' => ['number'],
            'tooltip' => 'supported attributes types: number',
        ],
        [
            'name'  => 'reference',
            'label' => 'prestashop::app.prestashop.fields.reference',
            'types' => ['text'],
            'tooltip' => 'supported attributes types: text',
        ],
    ];

    /**
     * Get PrestaShop mapping fields.
     */
    public function getMappingField(): array
    {
        return $this->mappingFields;
    }
}
