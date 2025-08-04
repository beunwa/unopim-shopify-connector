<?php

use Webkul\Attribute\Models\Attribute;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('should show the prestashop import-mappings page', function () {
    $this->loginAsAdmin();

    get(route('admin.prestashop.import-mappings', 3))
        ->assertStatus(200)
        ->assertSeeText(trans('shopify::app.shopify.import.mapping.title'));
});

it('should update the import mapping', function () {
    $this->loginAsAdmin();

    $name = Attribute::factory()->create(['type' => 'text']);
    $description = Attribute::factory()->create(['type' => 'textarea']);
    $price = Attribute::factory()->create(['type' => 'price']);
    $weight = Attribute::factory()->create(['type' => 'text']);

    $importMapping = [
        'title'           => $name->code,
        'descriptionHtml' => $description->code,
        'price'           => $price->code,
        'weight'          => $weight->code,
    ];

    post(route('prestashop.import-mappings.create'), $importMapping)
        ->assertStatus(302)
        ->assertSessionHas(['success']);
});
