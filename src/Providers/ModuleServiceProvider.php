<?php

namespace Webkul\Prestashop\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Prestashop\Models\ShopifyCredentialsConfig::class,
        \Webkul\Prestashop\Models\ShopifyExportMappingConfig::class,
        \Webkul\Prestashop\Models\ShopifyMappingConfig::class,
        \Webkul\Prestashop\Models\ShopifyMetaFieldsConfig::class,
    ];
}
