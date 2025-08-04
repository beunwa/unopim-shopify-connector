<?php

namespace Webkul\Prestashop\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Prestashop\Models\PrestashopMetaFieldsConfig;

class PrestashopMetaFieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrestashopMetaFieldsConfig::class;

    /**
     * Define the model's default state.
     * Fake credentials are used for the testing purposes.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code'           => 'ps_test',
            'attribute'      => 'ps_attribute',
            'name_space'     => 'prestashop',
            'name_space_key' => 'prestashop_key',
            'type'           => 'text',
            'ownerType'      => 'shop',
        ];
    }
}
