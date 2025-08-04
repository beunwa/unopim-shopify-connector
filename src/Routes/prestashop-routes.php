<?php

use Illuminate\Support\Facades\Route;
use Webkul\Prestashop\Http\Controllers\CredentialController;
use Webkul\Prestashop\Http\Controllers\ImportMappingController;
use Webkul\Prestashop\Http\Controllers\MappingController;
use Webkul\Prestashop\Http\Controllers\MetaFieldController;
use Webkul\Prestashop\Http\Controllers\OptionController;
use Webkul\Prestashop\Http\Controllers\SettingController;

/**
 * Catalog routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('prestashop')->group(function () {

        Route::controller(CredentialController::class)->prefix('credentials')->group(function () {
            Route::get('', 'index')->name('prestashop.credentials.index');

            Route::post('create', 'store')->name('prestashop.credentials.store');

            Route::get('edit/{id}', 'edit')->name('prestashop.credentials.edit');

            Route::put('update/{id}', 'update')->name('prestashop.credentials.update');

            Route::delete('delete/{id}', 'destroy')->name('prestashop.credentials.delete');
        });

        Route::controller(MetaFieldController::class)->prefix('metafields')->group(function () {
            Route::get('', 'index')->name('prestashop.metafield.index');

            Route::post('create', 'store')->name('prestashop.metafield.store');

            Route::get('edit/{id}', 'edit')->name('prestashop.metafield.edit');

            Route::put('update/{id}', 'update')->name('prestashop.metafield.update');

            Route::delete('delete/{id}', 'destroy')->name('prestashop.metafield.delete');

            Route::post('mass-delete', 'massDestroy')->name('prestashop.metafield.mass_delete');
        });

        Route::prefix('export')->group(function () {
            Route::controller(SettingController::class)->prefix('settings')->group(function () {
                Route::get('{id}', 'index')->name('admin.prestashop.settings');

                Route::post('create', 'store')->name('prestashop.export-settings.create');
            });
            Route::controller(MappingController::class)->prefix('mapping')->group(function () {
                Route::get('{id}', 'index')->name('admin.prestashop.export-mappings');

                Route::post('create', 'store')->name('prestashop.export-mappings.create');
            });

        });

        Route::prefix('import')->group(function () {
            Route::controller(ImportMappingController::class)->prefix('mapping')->group(function () {
                Route::get('{id}', 'index')->name('admin.prestashop.import-mappings');

                Route::post('create', 'store')->name('prestashop.import-mappings.create');
            });
        });

        Route::controller(OptionController::class)->group(function () {

            Route::get('get-attribute', 'listAttributes')->name('admin.prestashop.get-attribute');

            Route::get('get-image-attribute', 'listImageAttributes')->name('admin.prestashop.get-image-attribute');

            Route::get('get-gallery-attribute', 'listGalleryAttributes')->name('admin.prestashop.get-gallery-attribute');

            Route::get('get-metafield-attribute', 'listMetafieldAttributes')->name('admin.prestashop.get-metafield-attribute');

            Route::get('selected-metafield-attribute', 'selectedMetafieldAttributes')->name('admin.prestashop.get-selected-attribute');

            Route::get('get-prestashop-credentials', 'listPrestashopCredential')->name('prestashop.credential.fetch-all');

            Route::get('get-prestashop-channel', 'listChannel')->name('prestashop.channel.fetch-all');

            Route::get('get-prestashop-currency', 'listCurrency')->name('prestashop.currency.fetch-all');

            Route::get('get-prestashop-locale', 'listLocale')->name('prestashop.locale.fetch-all');

            Route::get('get-prestashop-attrGroup', 'listAttributeGroup')->name('prestashop.attribute-group.fetch-all');

            Route::get('get-prestashop-family', 'listShopifyFamily')->name('admin.prestashop.get-all-family-variants');
        });

    });
});
