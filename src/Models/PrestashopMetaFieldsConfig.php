<?php

namespace Webkul\Prestashop\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\HistoryControl\Contracts\HistoryAuditable as HistoryContract;
use Webkul\HistoryControl\Interfaces\PresentableHistoryInterface;
use Webkul\HistoryControl\Traits\HistoryTrait;
use Webkul\Prestashop\Contracts\PrestashopMetaFieldsConfig as PrestashopMetaFieldsContract;
use Webkul\Prestashop\Database\Factories\PrestashopMetaFieldFactory;
use Webkul\Prestashop\Presenters\JsonDataPresenter;

class PrestashopMetaFieldsConfig extends Model implements HistoryContract, PresentableHistoryInterface, PrestashopMetaFieldsContract
{
    use HasFactory, HistoryTrait;

    protected $table = 'wk_prestashop_metafield_defination';

    protected $historyTags = ['prestashop_meta_fields'];

    protected $fillable = [
        'ownerType',
        'code',
        'type',
        'attribute',
        'name_space_key',
        'description',
        'validations',
        'listvalue',
        'pin',
        'options',
        'storefronts',
        'ContentTypeName',
        'apiUrl',
    ];

    protected $casts = [
        'validations' => 'string',
        'options'     => 'string',
    ];

    /**
     * custom history presenters to be used while displaying the history for that column
     */
    public static function getPresenters(): array
    {
        return [
            'validations' => JsonDataPresenter::class,
            'options'     => JsonDataPresenter::class,
        ];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return PrestashopMetaFieldFactory::new();
    }
}
