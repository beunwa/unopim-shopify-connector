<?php

namespace Webkul\Prestashop\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Prestashop\Contracts\PrestashopMappingConfig as PrestashopMappingConfigContract;

class PrestashopMappingConfig extends Model implements PrestashopMappingConfigContract
{
    protected $table = 'wk_prestashop_data_mapping';

    protected $fillable = [
        'entityType',
        'code',
        'externalId',
        'jobInstanceId',
        'relatedId',
        'relatedSource',
        'apiUrl',
    ];
}
