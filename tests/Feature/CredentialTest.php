<?php

use Illuminate\Support\Facades\Http;
use Webkul\Prestashop\Models\PrestashopCredentialsConfig;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('should returns the prestashop credential index page', function () {
    $this->loginAsAdmin();

    get(route('prestashop.credentials.index'))
        ->assertStatus(200)
        ->assertSeeText(trans('shopify::app.shopify.credential.index.title'));
});

it('should return the prestashop credential edit page', function () {
    $this->loginAsAdmin();

    $prestashopCredential = PrestashopCredentialsConfig::factory()->create();

    get(route('prestashop.credentials.edit', ['id' => $prestashopCredential->id]))
        ->assertStatus(200);
});

it('should create the prestashop credential with valid input', function () {
    $this->loginAsAdmin();

    Http::fake([
        'https://test.prestashop.com/api' => Http::response(['code' => 200], 200),
    ]);

    $prestashopCredential = [
        'apiKey' => 'test_access_token',
        'shopUrl'     => 'https://test.prestashop.com',
    ];

    post(route('prestashop.credentials.store'), $prestashopCredential)
        ->assertStatus(200);
});

it('should return error for invalid URL during credential create', function () {
    $this->loginAsAdmin();

    $prestashopCredential = [
        'apiKey' => 'test_access_token',
        'shopUrl'     => 'test.prestashop.com',
    ];

    $response = post(route('prestashop.credentials.store'), $prestashopCredential)
        ->assertStatus(422);

    $this->assertArrayHasKey('errors', $response->json());
    $this->assertEquals(trans('shopify::app.shopify.credential.invalidurl'), $response->json('errors.shopUrl.0'));
});

it('should return error for invalid credentials ', function () {
    $this->loginAsAdmin();

    Http::fake([
        'https://test.prestashop.com/api' => Http::response(['code' => 401], 401),
    ]);

    $prestashopCredential = [
        'apiKey' => 'test_access_token',
        'shopUrl'     => 'https://test.prestashop.com',
    ];

    $response = post(route('prestashop.credentials.store'), $prestashopCredential)
        ->assertStatus(422);

    $this->assertArrayHasKey('errors', $response->json());
    $this->assertEquals(trans('shopify::app.shopify.credential.invalid'), $response->json('errors.shopUrl.0'));
    $this->assertEquals(trans('shopify::app.shopify.credential.invalid'), $response->json('errors.apiKey.0'));
});

it('should update the prestashop credential successfully', function () {
    $this->loginAsAdmin();

    $credential = PrestashopCredentialsConfig::factory()->create([
        'apiKey' => 'valid_access_token',
        'shopUrl'     => 'https://test.prestashop.com',
    ]);

    Http::fake([
        'https://test.prestashop.com/api' => Http::response(['code' => 200], 200),
    ]);

    $updatedData = [
        'shopUrl'      => 'https://test.prestashop.com',
        'apiKey'  => 'valid_access_token',
        'storeLocales' => json_encode([['locale' => 'en', 'primary' => true]]),
        'salesChannel' => 'online',
        'locations'    => 'location1',
    ];

    $response = $this->put(route('prestashop.credentials.update', ['id' => $credential->id]), $updatedData);

    $response->assertRedirect(route('prestashop.credentials.edit', ['id' => $credential->id]));
    $response->assertSessionHas('success', trans('shopify::app.shopify.credential.update-success'));

    $this->assertDatabaseHas('wk_prestashop_credentials_config', [
        'id'      => $credential->id,
        'shopUrl' => 'https://test.prestashop.com',
    ]);
});

it('should returns the prestashop credential edit page, with validation', function () {
    $this->loginAsAdmin();

    $prestashopCredential = PrestashopCredentialsConfig::factory()->create();
    $updatedCredential = [
        'id'           => $prestashopCredential->id,
        'apiKey'  => $prestashopCredential->apiKey,
        'shopUrl'      => $prestashopCredential->shopUrl,
        'storeLocales' => [],
        'active'       => 0,
    ];

    put(route('prestashop.credentials.update', $prestashopCredential->id), $updatedCredential)
        ->assertStatus(302)
        ->assertSessionHasErrors(['shopUrl', 'apiKey']);
});

it('should delete the prestashop credential', function () {
    $this->loginAsAdmin();

    $prestashopCredential = PrestashopCredentialsConfig::factory()->create();

    delete(route('prestashop.credentials.delete', $prestashopCredential->id))
        ->assertStatus(200);

    $this->assertDatabaseMissing($this->getFullTableName(PrestashopCredentialsConfig::class), [
        'id' => $prestashopCredential->id,
    ]);
});
