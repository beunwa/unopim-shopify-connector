<?php

use Webkul\DataTransfer\Models\JobInstances;

use function Pest\Laravel\postJson;

it('should create the prestashop category export job', function () {
    $this->loginAsAdmin();

    $exportJob = [
        'code'            => fake()->unique()->word,
        'entity_type'     => 'prestashopCategories',
        'field_separator' => ',',
        'filters'         => [
            'file_format' => 'Csv',
            'with_media'  => 1,
        ],
    ];

    $response = postJson(route('admin.settings.data_transfer.exports.store'), $exportJob);
    $response->assertStatus(302)
        ->assertSessionHas('success');

    $this->assertDatabaseHas($this->getFullTableName(JobInstances::class), [
        'code'        => $exportJob['code'],
        'entity_type' => 'prestashopCategories',
    ]);
});

it('should create the prestashop product export job', function () {
    $this->loginAsAdmin();

    $exportJob = [
        'code'            => fake()->unique()->word,
        'entity_type'     => 'prestashopProduct',
        'field_separator' => ',',
        'filters'         => [
            'file_format' => 'Csv',
            'with_media'  => 1,
        ],
    ];

    $response = postJson(route('admin.settings.data_transfer.exports.store'), $exportJob);
    $response->assertStatus(302)
        ->assertSessionHas('success');

    $this->assertDatabaseHas($this->getFullTableName(JobInstances::class), [
        'code'        => $exportJob['code'],
        'entity_type' => 'prestashopProduct',
    ]);
});
