<?php

namespace Webkul\Prestashop\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\HistoryControl\Contracts\HistoryAuditable as HistoryContract;
use Webkul\HistoryControl\Interfaces\PresentableHistoryInterface;
use Webkul\HistoryControl\Traits\HistoryTrait;
use Webkul\Prestashop\Contracts\ShopifyExportMappingConfig as ShopifyExportMappingConfigContract;
use Webkul\Prestashop\Presenters\JsonDataPresenter;

class ShopifyExportMappingConfig extends Model implements HistoryContract, PresentableHistoryInterface, ShopifyExportMappingConfigContract
{
    use HistoryTrait;

    protected $table = 'shopify_setting_configuration_values';

    protected $historyTags = ['shopify_exportmapping'];

    protected $fillable = [
        'name',
        'mapping',
    ];

    protected $casts = [
        'mapping' => 'array',
    ];

    /**
     * custom history presenters to be used while displaying the history for that column
     */
    public static function getPresenters(): array
    {
        return [
            'mapping' => JsonDataPresenter::class,
        ];
    }
}
