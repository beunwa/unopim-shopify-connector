<?php

namespace Webkul\Prestashop\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Prestashop\Models\PrestashopCredentialsConfig::class,
        \Webkul\Prestashop\Models\PrestashopExportMappingConfig::class,
        \Webkul\Prestashop\Models\PrestashopMappingConfig::class,
        \Webkul\Prestashop\Models\PrestashopMetaFieldsConfig::class,
    ];
}
