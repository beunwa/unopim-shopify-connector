<?php

namespace Webkul\Prestashop\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\HistoryControl\Contracts\HistoryAuditable as HistoryContract;
use Webkul\HistoryControl\Interfaces\PresentableHistoryInterface;
use Webkul\HistoryControl\Traits\HistoryTrait;
use Webkul\Prestashop\Contracts\PrestashopCredentialsConfig as PrestashopCredentialsContract;
use Webkul\Prestashop\Database\Factories\PrestashopCredentialFactory;
use Webkul\Prestashop\Presenters\JsonDataPresenter;

class PrestashopCredentialsConfig extends Model implements HistoryContract, PresentableHistoryInterface, PrestashopCredentialsContract
{
    use HasFactory, HistoryTrait;

    protected $table = 'wk_prestashop_credentials_config';

    protected $historyTags = ['prestashop_credentials'];

    protected $auditExclude = ['storeLocales', 'apiKey'];

    protected $fillable = [
        'shopUrl',
        'apiKey',
        'active',
        'apiVersion',
        'storelocaleMapping',
        'storeLocales',
        'defaultSet',
        'resources',
        'extras',
        'salesChannel',
    ];

    protected $casts = [
        'storelocaleMapping' => 'array',
        'storeLocales'       => 'array',
        'extras'             => 'array',
    ];

    /**
     * custom history presenters to be used while displaying the history for that column
     */
    public static function getPresenters(): array
    {
        return [
            'storelocaleMapping' => JsonDataPresenter::class,
            'extras'             => JsonDataPresenter::class,
        ];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return PrestashopCredentialFactory::new();
    }
}
