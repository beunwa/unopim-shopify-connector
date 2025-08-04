<?php

use Webkul\Prestashop\Models\PrestashopMetaFieldsConfig;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('should returns the prestashop Metafield Definitions index page', function () {
    $this->loginAsAdmin();

    get(route('prestashop.metafield.index'))
        ->assertStatus(200)
        ->assertSeeText(trans('shopify::app.shopify.metafield.index.title'));
});

it('should returns the prestashop Metafield Definitions edit page', function () {
    $this->loginAsAdmin();

    $prestashopMetafield = PrestashopMetaFieldsConfig::factory()->create();

    get(route('prestashop.metafield.edit', ['id' => $prestashopMetafield->id]))
        ->assertStatus(200);
});

it('should create the prestashop Metafield Definitions with valid input', function () {
    $this->loginAsAdmin();

    $prestashopMetaField = [
        'ownerType'          => 'test_ownerType',
        'code'               => 'test_code',
        'type'               => 'test_type',
        'name_space_key'     => 'test_name.space_key',
        'pin'                => '1',
        'attribute'          => 'test_attribute',
    ];

    post(route('prestashop.metafield.store'), $prestashopMetaField)
        ->assertStatus(200);
});

it('should update the prestashop Metafield Definitions with valid input', function () {
    $this->loginAsAdmin();
    $metaField = PrestashopMetaFieldsConfig::factory()->create([
        'ownerType'          => 'test_ownerType',
        'code'               => 'test_code',
        'type'               => 'test_type',
        'name_space_key'     => 'test_name.space_key',
        'pin'                => '0',
        'attribute'          => 'test_attribute',
    ]);

    $updatedData = [
        'pin'                => '0',
        'type'               => 'test_type',
        'storefronts'        => '1',
    ];

    $response = $this->put(route('prestashop.metafield.update', ['id' => $metaField->id]), $updatedData);

    $response->assertRedirect(route('prestashop.metafield.edit', ['id' => $metaField->id]));

    $response->assertSessionHas('success', trans('shopify::app.shopify.metafield.update-success'));
});
