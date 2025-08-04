<?php

namespace Webkul\Prestashop\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Prestashop\Contracts\ShopifyExportMappingConfig;

class ShopifyExportMappingRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ShopifyExportMappingConfig::class;
    }
}
