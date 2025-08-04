<?php

namespace Webkul\Prestashop\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Prestashop\Contracts\ShopifyMetaFieldsConfig;

class ShopifyMetaFieldRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ShopifyMetaFieldsConfig::class;
    }
}
