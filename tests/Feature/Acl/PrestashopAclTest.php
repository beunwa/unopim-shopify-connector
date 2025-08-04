<?php

use Illuminate\Support\Facades\Http;
use Webkul\Prestashop\Models\PrestashopCredentialsConfig;

it('should not display the prestashop credentials index if does not have permission', function () {
    $this->loginWithPermissions();

    $this->get(route('prestashop.credentials.index'))
        ->assertSeeText('Unauthorized');
});

it('should display the prestashop credentials index if has permission', function () {
    $this->loginWithPermissions(permissions: ['prestashop', 'prestashop.credentials']);

    $this->get(route('prestashop.credentials.index'))
        ->assertSeeText(trans('prestashop::app.components.layouts.sidebar.prestashop'))
        ->assertStatus(200);
});

it('should not display the create prestashop credentials form if does not have permission', function () {
    $this->loginWithPermissions();

    $this->post(route('prestashop.credentials.store'))
        ->assertSeeText('Unauthorized');
});

it('should display the create prestashop credentials form if has permission', function () {
    $this->loginWithPermissions(permissions: ['prestashop.credentials.create']);

    Http::fake([
        'https://test.prestashop.com/api' => Http::response(['code' => 200], 200),
    ]);

    $prestashopCredential = [
        'apiKey' => 'test_access_token',
        'shopUrl'     => 'https://test.prestashop.com',
    ];

    $this->post(route('prestashop.credentials.store'), $prestashopCredential)
        ->assertStatus(200);
});

it('should not display the prestashop credentials edit form if does not have permission', function () {
    $this->loginWithPermissions();

    $this->get(route('prestashop.credentials.edit', ['id' => 1]))
        ->assertSeeText('Unauthorized');
});

it('should display the prestashop credentials edit form if has permission', function () {
    $this->loginWithPermissions(permissions: ['prestashop.credentials.edit']);

    $prestashopCredential = PrestashopCredentialsConfig::factory()->create();

    $this->get(route('prestashop.credentials.edit', ['id' => $prestashopCredential->id]))
        ->assertStatus(200);
});

it('should not allow deleting prestashop credentials if does not have permission', function () {
    $this->loginWithPermissions();

    $prestashopCredential = PrestashopCredentialsConfig::factory()->create();

    $this->delete(route('prestashop.credentials.delete', ['id' => $prestashopCredential->id]))
        ->assertSeeText('Unauthorized');
});

it('should allow deleting prestashop credentials if has permission', function () {
    $this->loginWithPermissions(permissions: ['prestashop.credentials.delete']);
    $prestashopCredential = PrestashopCredentialsConfig::factory()->create();

    $this->delete(route('prestashop.credentials.delete', $prestashopCredential->id))
        ->assertStatus(200);

    $this->assertDatabaseMissing($this->getFullTableName(PrestashopCredentialsConfig::class), [
        'id' => $prestashopCredential->id,
    ]);
});

it('should not display the prestashop export mappings if does not have permission', function () {
    $this->loginWithPermissions();

    $this->get(route('admin.prestashop.export-mappings', ['id' => 2]))
        ->assertSeeText('Unauthorized');
});

it('should display the prestashop export mappings if has permission', function () {
    $this->loginWithPermissions(permissions: ['prestashop', 'prestashop.export-mappings']);

    $this->get(route('admin.prestashop.export-mappings', 1))
        ->assertStatus(200)
        ->assertSeeText(trans('shopify::app.shopify.export.mapping.title'));
});

it('should not display the prestashop settings if does not have permission', function () {
    $this->loginWithPermissions();

    $this->get(route('admin.prestashop.settings', ['id' => 1]))
        ->assertSeeText('Unauthorized');
});

it('should display the prestashop settings if has permission', function () {
    $this->loginWithPermissions(permissions: ['prestashop', 'prestashop.settings']);

    $this->get(route('admin.prestashop.settings', ['id' => 1]))
        ->assertSeeText(trans('shopify::app.components.layouts.sidebar.settings'))
        ->assertStatus(200);
});
