<?php

namespace Webkul\Prestashop\Helpers;

class PrestashopMetaFieldType
{
    /**
     * PrestaShop MetaField Types.
     */
    public array $metaFieldType = [
        'text' => [
            ['id' => 'string', 'name' => 'String'],
        ],
        'textarea' => [
            ['id' => 'text', 'name' => 'Text'],
        ],
        'boolean' => [
            ['id' => 'bool', 'name' => 'Boolean'],
        ],
        'select' => [
            ['id' => 'select', 'name' => 'Select'],
        ],
        'multiselect' => [
            ['id' => 'multiselect', 'name' => 'Multi Select'],
        ],
        'date' => [
            ['id' => 'date', 'name' => 'Date'],
        ],
        'decimal' => [
            ['id' => 'decimal', 'name' => 'Decimal'],
        ],
        'number' => [
            ['id' => 'number', 'name' => 'Number'],
        ],
    ];

    /**
     * Get PrestaShop meta field types.
     */
    public function getMetaFieldType(): array
    {
        return $this->metaFieldType;
    }

    /**
     * Get PrestaShop meta field types (alias for backward compatibility).
     */
    public function getMetaFieldTypeInPrestashop(): array
    {
        return $this->metaFieldType;
    }
}
