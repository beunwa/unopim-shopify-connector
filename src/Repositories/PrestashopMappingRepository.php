<?php

namespace Webkul\Prestashop\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Prestashop\Contracts\PrestashopMappingConfig;

class PrestashopMappingRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return PrestashopMappingConfig::class;
    }
}
