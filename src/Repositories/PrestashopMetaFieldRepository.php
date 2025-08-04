<?php

namespace Webkul\Prestashop\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Prestashop\Contracts\PrestashopMetaFieldsConfig;

class PrestashopMetaFieldRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return PrestashopMetaFieldsConfig::class;
    }
}
