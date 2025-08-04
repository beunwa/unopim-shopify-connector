<?php

namespace Webkul\Prestashop\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Prestashop\Contracts\ShopifyMappingConfig;

class ShopifyMappingRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ShopifyMappingConfig::class;
    }
}
