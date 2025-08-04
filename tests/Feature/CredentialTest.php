<?php

use Illuminate\Support\Facades\Http;
use Webkul\Prestashop\Models\PrestashopCredentialsConfig;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('should returns the shopify credential index page', function () {
    $this->loginAsAdmin();

    get(route('prestashop.credentials.index'))
        ->assertStatus(200)
        ->assertSeeText(trans('shopify::app.shopify.credential.index.title'));
});

it('should returns the shopify credential edit page', function () {
    $this->loginAsAdmin();

    $shopifyCredential = PrestashopCredentialsConfig::factory()->create();

    get(route('prestashop.credentials.edit', ['id' => $shopifyCredential->id]))
        ->assertStatus(200);
});

it('should create the shopify credential with valid input', function () {
    $this->loginAsAdmin();

    Http::fake([
        'https://test.myshopify.com/admin/api/2023-04/graphql.json' => Http::response(['code' => 200], 200),
    ]);

    $shopifyCredential = [
        'apiKey' => 'test_access_token',
        'apiVersion'  => '2023-04',
        'shopUrl'     => 'https://test.myshopify.com',
    ];

    post(route('prestashop.credentials.store'), $shopifyCredential)
        ->assertStatus(200);
});

it('should return error for invalid URL during credential create', function () {
    $this->loginAsAdmin();

    $shopifyCredential = [
        'apiKey' => 'test_access_token',
        'apiVersion'  => '2023-04',
        'shopUrl'     => 'test.myshopify.com',
    ];

    $response = post(route('prestashop.credentials.store'), $shopifyCredential)
        ->assertStatus(422);

    $this->assertArrayHasKey('errors', $response->json());
    $this->assertEquals(trans('shopify::app.shopify.credential.invalidurl'), $response->json('errors.shopUrl.0'));
});

it('should return error for invalid credentials ', function () {
    $this->loginAsAdmin();

    Http::fake([
        'https://test.myshopify.com/admin/api/2023-04/graphql.json' => Http::response(['code' => 401], 401),
    ]);

    $shopifyCredential = [
        'apiKey' => 'test_access_token',
        'apiVersion'  => '2023-04',
        'shopUrl'     => 'https://test.myshopify.com',
    ];

    $response = post(route('prestashop.credentials.store'), $shopifyCredential)
        ->assertStatus(422);

    $this->assertArrayHasKey('errors', $response->json());
    $this->assertEquals(trans('shopify::app.shopify.credential.invalid'), $response->json('errors.shopUrl.0'));
    $this->assertEquals(trans('shopify::app.shopify.credential.invalid'), $response->json('errors.apiKey.0'));
});

it('should update the shopify credential successfully', function () {
    $this->loginAsAdmin();

    $credential = PrestashopCredentialsConfig::factory()->create([
        'apiKey' => 'valid_access_token',
        'shopUrl'     => 'https://test.myshopify.com',
        'apiVersion'  => '2023-04',
    ]);

    Http::fake([
        'https://test.myshopify.com/admin/api/2023-04/graphql.json' => Http::response(['code' => 200], 200),
    ]);

    $updatedData = [
        'shopUrl'      => 'https://test.myshopify.com',
        'apiKey'  => 'valid_access_token',
        'storeLocales' => json_encode([['locale' => 'en', 'primary' => true]]),
        'salesChannel' => 'online',
        'locations'    => 'location1',
        'apiVersion'   => '2023-04',
    ];

    $response = $this->put(route('prestashop.credentials.update', ['id' => $credential->id]), $updatedData);

    $response->assertRedirect(route('prestashop.credentials.edit', ['id' => $credential->id]));
    $response->assertSessionHas('success', trans('shopify::app.shopify.credential.update-success'));

    $this->assertDatabaseHas('wk_prestashop_credentials_config', [
        'id'      => $credential->id,
        'shopUrl' => 'https://test.myshopify.com',
    ]);
});

it('should returns the shopify credential edit page, with validation', function () {
    $this->loginAsAdmin();

    $shopifyCredential = PrestashopCredentialsConfig::factory()->create();
    $updatedCredential = [
        'id'           => $shopifyCredential->id,
        'apiKey'  => $shopifyCredential->apiKey,
        'apiVersion'   => $shopifyCredential->apiVersion,
        'shopUrl'      => $shopifyCredential->shopUrl,
        'storeLocales' => [],
        'active'       => 0,
    ];

    put(route('prestashop.credentials.update', $shopifyCredential->id), $updatedCredential)
        ->assertStatus(302)
        ->assertSessionHasErrors(['shopUrl', 'apiKey']);
});

it('should delete the shopify credential', function () {
    $this->loginAsAdmin();

    $shopifyCredential = PrestashopCredentialsConfig::factory()->create();

    delete(route('prestashop.credentials.delete', $shopifyCredential->id))
        ->assertStatus(200);

    $this->assertDatabaseMissing($this->getFullTableName(PrestashopCredentialsConfig::class), [
        'id' => $shopifyCredential->id,
    ]);
});
