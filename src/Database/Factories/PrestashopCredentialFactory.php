<?php

namespace Webkul\Prestashop\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Prestashop\Models\PrestashopCredentialsConfig;

class PrestashopCredentialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrestashopCredentialsConfig::class;

    /**
     * Define the model's default state.
     * Fake credentials are used for the testing purposes.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shopUrl' => 'https://demo.prestashop.com',
            'apiKey'  => 'PRESTASHOP_API_KEY',
        ];
    }
}
