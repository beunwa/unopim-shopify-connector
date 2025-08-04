<?php

namespace Webkul\Prestashop\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Prestashop\Contracts\PrestashopCredentialsConfig;

class PrestashopCredentialRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return PrestashopCredentialsConfig::class;
    }
}
